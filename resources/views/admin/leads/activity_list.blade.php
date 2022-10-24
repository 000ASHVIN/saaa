<li class="timeline-item success">
    <div class="margin-left-15">
        <div class="text-muted text-small">
            {{ $activity->created_at->diffForHumans() }}
        </div>
        <p>
            <?php 
                $action = '';
                $data = $activity->activityData();
                if ($activity->action == 'download_brochure') {
                    $action = ' downloaded brochure';
                    if(!empty($data->course_name)) {
                        $action .= ' for course <strong>'.$data->course_name.'</strong>';
                    }
                }
                elseif($activity->action == 'talk_to_human') {
                    $action = ' requested talk to human';
                    if(!empty($data->course_name)) {
                        $action .= ' for course <strong>'.$data->course_name.'</strong>';
                    }
                    $action .= '.';
                }
                else {
                    $action = str_replace('_',' ', $activity->action);
                }
            ?>
            {{ $lead->first_name }} {!! $action !!} 
        </p>
    </div>
</li>