<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->orderByDesc('created_at');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('modulo')) {
            $query->where('modulo', $request->modulo);
        }

        if ($request->filled('acao')) {
            $query->where('acao', $request->acao);
        }

        if ($request->filled('data_inicio')) {
            $query->whereDate('created_at', '>=', $request->data_inicio);
        }

        if ($request->filled('data_fim')) {
            $query->whereDate('created_at', '<=', $request->data_fim);
        }

        $logs     = $query->paginate(20)->withQueryString();
        $usuarios = User::orderBy('name')->get();
        $modulos  = ActivityLog::distinct()->pluck('modulo');
        $acoes    = ActivityLog::distinct()->pluck('acao');

        return view('logs.index', compact('logs', 'usuarios', 'modulos', 'acoes'));
    }

    public function show(ActivityLog $log)
    {
        $log->load('user');
        return view('logs.show', compact('log'));
    }
}