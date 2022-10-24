<?php

namespace App\Repositories\DatatableRepository;

use App\Billing\Invoice;
use App\Models\Course;
use App\Resubscribe;
use App\Thread;
use Yajra\Datatables\Facades\Datatables;
use App\SponsorFormSubmission;
use App\SponsorList;
use App\Unsubscribe;
use App\Users\Industry;

class DatatableRepository
{
    public function support_tickets()
    {
        $thread = Thread::query();
        $request = request();

        // Filter data
        if($request->priority) {
            $thread->where('priority',$request->priority);
        }

        if($request->status) {
            $thread->where('status',$request->status);
        }

        if($request->title) {
            $thread->where('title','LIKE','%'.$request->title.'%');
        }

        if(auth()->user()->is('admin')) {

            if($request->agent_group_id) {
                $thread->whereIn('agent_group_id',$request->agent_group_id);
            }

            if($request->agent_id) {
                $thread->whereIn('agent_id',$request->agent_id);
            }
        
        }
        else {
            $thread->where('agent_id',auth()->user()->id);
        }

        return Datatables::of($thread)
            ->editColumn('title', function ($thread) {
                return ucwords($thread->title);
            })
            ->editColumn('replies', function ($thread) {
                return count($thread->tickets);
            })
            ->editColumn('open_to_public', function ($thread) {
                return ($thread->open_to_public ? "Yes" : "No");
            })
            ->editColumn('agent_group', function ($thread) {
                if($thread->agentGroup) {
                    return (ucwords(strtolower($thread->agentGroup->name)));
                }
                return ('-');
            })
            ->editColumn('agent', function ($thread) {
                if($thread->agent) {
                    return (ucwords(strtolower($thread->agent->name)));
                }
                return ('-');
            })
            ->editColumn('priorityText', function ($thread) {
                return $thread->priorityText;
            })
            ->editColumn('statusText', function ($thread) {
                return $thread->statusText;
            })
            ->editColumn('category', function ($thread) {
                if($thread->cat) {
                    return (ucwords(strtolower($thread->cat->title)));
                }
                return ('-');
            })
            ->editColumn('category_id', function ($thread) {
                return $thread->category;
            })
            ->make(true);
    }

    public function list_courses()
    {
        $model = Course::query();

        return Datatables::eloquent($model)->order(function ($query) {
                $query->orderBy('id', 'desc');
            })
            ->editColumn('title', function ($course) {
                return str_limit($course->title, 50);
            })
            ->editColumn('students', function ($course) {
                return count($course->users);
            })
            ->editColumn('start_date', function ($course) {
                return date_format($course->start_date, 'Y/m/d');
            })
            ->editColumn('end_date', function ($course) {
                return date_format($course->end_date, 'Y/m/d');
            })
            ->make(true);
    }

    public function list_students($course)
    {
        return Datatables::of($course->users)
            ->editColumn('name', function ($user) {
                return $user->name;
            })
            ->make(true);
    }

    public function list_course_invoices($course)
    {
        $invoices = Invoice::whereIn('id', $course->invoices()->toArray())->get();

        return Datatables::of($invoices)
            ->editColumn('balance', function ($invoice) {
                return ($invoice->transactions()->whereType('debit')->sum('amount') - $invoice->transactions()->whereType('credit')->sum('amount')) / 100;
            })

            ->editColumn('client', function ($invoice) {
                return ucwords($invoice->user->name);
            })

            ->make(true);
    }


    public function list_reward()
    {
        $SponsorFormSubmission = SponsorFormSubmission::all();

        return Datatables::of($SponsorFormSubmission)
        ->editColumn('name', function ($SponsorFormSubmission) {
            return str_limit($SponsorFormSubmission->name, 50);
        })

            ->editColumn('email', function ($SponsorFormSubmission) {
                return ucwords($SponsorFormSubmission->email);
            })

            ->make(true);
    }

   

    public function list_sponsor()
    {
        return Datatables::of(SponsorList::all())
            ->editColumn('name', function ($sponsor) {
                return str_limit($sponsor->name, 50);
            })->make(true);
    }

    public function list_industries()
    {
        $industries = Industry::all();

        return Datatables::of($industries)->make(true);
    }

    public function list_synced_courses()
    {
        $synced_courses = Course::where('moodle_course_id', '>', 0);

        return Datatables::of($synced_courses)->make(true);
    }

    public function list_unsubscribers()
    {
        $unsubscribers = Unsubscribe::all();

        return Datatables::of($unsubscribers)
                        ->editColumn('reason', function ($unsubscriber) {
                            if($unsubscriber->reason) {
                                $reason = json_decode($unsubscriber->reason);
                                if(is_array($reason)) {
                                    $reason = implode(',<br> ', $reason);
                                    return $reason;
                                }
                            } else {
                                return "-";
                            }
                        })->make(true);
    }
    
    public function list_resubscribers()
    {
        $resubscribers = Resubscribe::all();

        return Datatables::of($resubscribers)
                        ->editColumn('unsubscribe_all', function ($resubscriber) {
                            if($resubscriber->unsubscribe_all) {
                                return "Yes";
                            } else {
                                return "No";
                            }
                        })
                        ->editColumn('subscribed_types', function ($resubscriber) {
                            if($resubscriber->unsubscribe_all) {
                                return "-";
                            } else {
                                $subscribed_types = json_decode($resubscriber->subscribed_types);
                                if(is_array($subscribed_types)) {
                                    $subscribed_types = implode(',<br> ', $subscribed_types);
                                    return $subscribed_types;
                                }
                            }
                            
                        })->make(true);
    }
}