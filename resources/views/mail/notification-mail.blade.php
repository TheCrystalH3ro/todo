<div style="background-color: #424242;">
    <div style="padding: 1rem; background-color: #4db6ac">
        <h1 style="text-align: center; font-size: 4rem; color: white;"> TODO </h1>
    </div>
    <div style="background-color: #424242; padding: 4rem;">
        <div style="margin: 0 auto; text-align: center; background-color: white; border-radius: 1rem; padding: 2rem; width: max-content;">
            <p style="font-size: 1.2rem;">
                {!! get_notification_message($notification->type, $notification->user->name, $notification->task->name, $notification->sender->name) !!}
            </p>
            <div style="text-align: center">
                <a href="{{ url('/') }}" 
                    style="
                        background-color: #4db6ac;    
                        display: inline-block;
                        height: 32px;
                        font-size: 13px;
                        font-weight: 500;
                        line-height: 32px;
                        padding: 0 12px;
                        border-radius: 16px;
                        margin-bottom: 5px;
                        margin-right: 5px;
                        color: white;
                        font-size: 1rem;
                        text-decoration: none;
                        text-transform: uppercase;
                "> 
                    <b>Open app</b>
                </a>
            </div>
        </div>
    </div>
</div>