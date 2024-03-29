<div class="panel panel-default" v-if="{{ $vif }}">
    <div class="panel-body">
        <div class="donation-option">
            <label class="checkbox">
                <input v-model="{{ $vmodel }}" type="checkbox" value="1" v-on:change="{{ isset($vonchange)?$vonchange:'' }}">
                <i></i><span>Donate <strong>R@{{ donations }}</strong> to assist an underprivileged learner.</span>

                <span class="donation-hover"><small><em>How will my donation make a difference?</em></small></span>
                <div class="donation-tooltip">
                    <h5>How will my donation make a difference?</h5>
                    <h5>Shape a future:</h5>
                    <p>As a non-profit organisation, The Tax Faculty’s purpose is to empower and transform people’s lives. Over the past two years, we have successfully empowered over 440 learners in collaboration with public and private partnerships to fund, source and train unemployed youth to become world-class tax professionals. The Tax Faculty is a recognised SARS public benefit organisation and a level 1 B-BBEE contributor.<br/>
                    From as little as R100 you can help shape a future. As an additional benefit you will receive a section 18A certificate and a B-BBEE letter of confirmation. </p>
                </div>

                <div style="line-height: 22px;">
                    <small><em> Don't want to donate? Untick before you make payment</em></small>
                </div>
            </label>
        </div>

        <div class="clearfix"></div>
    </div>
</div>