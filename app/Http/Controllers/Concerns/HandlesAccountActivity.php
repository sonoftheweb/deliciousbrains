<?php


namespace App\Http\Controllers\Concerns;


use App\Events\CsvFileUploaded;
use App\Http\Resources\AccountActivity as AccountActivityResource;
use App\Http\Resources\AccountActivityCollection;
use App\Imports\ActivitiesImport;
use App\Models\AccountActivity;
use App\User;
use Exception;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class HandlesAccountActivity extends UseCaseConcerns
{
    use ValidatesRequests;

    /**
     * @var User|null
     */
    private $user;

    /**
     * @var string
     */
    private $csvPath;

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    /**
     * @throws ValidationException
     */
    public function saveAccountActivity()
    {
        if ($this->request->has('file')) {
            $this->validateFile()->getUser()->storeFile()->triggerEvent();
            return;
        }

        $this->validateActivity()->getUser()->saveActivityToUser();
    }

    /**
     * Validates request
     *
     * @return $this
     * @throws ValidationException
     */
    protected function validateActivity()
    {
        $rules = [
            'user_id' => 'required',
            'activity_date' => 'required',
            'amount' => 'required',
            'description' => 'required'
        ];

        $this->validate($this->request, $rules);

        return $this;
    }

    /**
     * @return $this
     */
    protected function getUser()
    {
        $this->user = User::query()->findOrFail($this->request->input('user_id'));

        return $this;
    }

    protected function saveActivityToUser()
    {
        $this->user->createActivity(
            $this->request->input('amount'),
            $this->request->input('description'),
            $this->request->input('activity_date')
        );

        return $this;
    }

    /**
     * @param AccountActivity $accountActivity
     * @throws ValidationException
     * @throws Exception
     */
    public function updateActivity(AccountActivity $accountActivity)
    {
        $this->validateActivity();

        $this->user = User::query()->findOrFail($this->request->user_id);

        $this->user->updateActivity($accountActivity, $this->request);
    }

    /**
     * Validates csv files
     *
     * @return $this
     * @throws ValidationException
     */
    protected function validateFile()
    {
        $rules = [
            'file' => 'required|max:2048|mimes:csv,txt',
        ];

        $this->validate($this->request, $rules);

        return $this;
    }

    /**
     * Stores the file on disc and gets the path
     *
     * @return $this
     */
    protected function storeFile()
    {
        //$this->csvPath = Storage::disk('csv')->putFileAs('/', $this->request->file, rand().'_'.$this->user->id.'_activities.csv');

        $this->csvPath = $this->request->file('file')->storeAs(
            '/',
            rand().'_'.$this->user->id.'_activities.csv',
            'csv'
        );

        return $this;
    }

    /**
     * Trigger an event that reads the csv file
     *
     * @return $this
     */
    protected function triggerEvent()
    {
        $importClass = new ActivitiesImport;

        Excel::import($importClass, storage_path('/app/public/csv/').$this->csvPath);

        $data = [
            'path' => $this->csvPath,
            'user' => $this->user,
            'row_count' => $importClass->data->count()
        ];

        event(new CsvFileUploaded($data));
        return $this;
    }

    /**
     * Simply get the activities
     *
     * @return AccountActivityCollection
     */
    public function getActivities()
    {
        $perPage = ($this->request->per_page) ? $this->request->per_page : 10;

        $activities = AccountActivity::query()->orderBy('activity_date', 'desc');

        return new AccountActivityCollection($activities->paginate($perPage));
    }
}
