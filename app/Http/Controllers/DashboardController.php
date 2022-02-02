<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\SurveyAnswerResource;
use App\Http\Resources\SurveyResourceDashboard;
use App\Models\Survey;
use App\Models\SurveyAnswer;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        //Total Number of surveys
        $total = Survey::query()->where('user_id', $user->id)->count();

        //Latest Surveys
        $latest = Survey::query()->where('user_id', $user->id)->latest('created_at')->first();

        //total Number of answers
        $totalAnswers = SurveyAnswer::query()
        ->join('surveys', 'survey_answers.survey_id', '=', 'surveys.id')
        ->where('surveys.user_id', $user->id)
        ->count();

        //Latest 5 answers
        $latestAnswers = SurveyAnswer::query()
        ->join('surveys', 'survey_answers.survey_id', '=', 'surveys.id')
        ->where('surveys.user_id', $user->id)
        ->orderBy('end_date', 'DESC')
        ->limit(5)
        ->getModels('survey_answers.*');

        return [
            'totalSurveys' => $total,
            'latestSurvey' => $latest ? new SurveyResourceDashboard($latest) : null,
            'totalAnswers' => $totalAnswers,
            'latestAnswers' => SurveyAnswerResource::collection($latestAnswers)
        ];
    }
}
