<div class="panel panel-default" v-if="forms.enroll.paymentOption == 'cc'">
    <div class="panel-heading">
        <div class="pull-left">
            Credit Card / Debit Card
        </div>

        <div class="clearfix"></div>
    </div>

    <div class="panel-body">

        <div class="alert alert-warning">
            <p><i class="fa fa-lightbulb-o"></i> <strong>Did you know</strong> that you can now use your debit card as a credit card for making online purchases? </p>
        </div>

        <div class="alert alert-info" v-if="user.cards.length == 0 && ! readyForThreeDs">
            <p>
                You have no credit card associated with your account yet, please update your credit card information in order to use this payment method.
            </p>
        </div>

        <label v-if="user.cards.length > 0 && ! readyForThreeDs">Select Credit Card</label>
        <ul class="list-group" v-if="user.cards.length > 0 && ! readyForThreeDs">
            <li class="list-group-item" v-for="card in user.cards">
                <label>
                    <input type="radio" name="card" v-model="forms.enroll.card" :value="card.id" :checked="card.id == user.primary_card">
                    @{{ card.number }}
                </label>
                <span>
	                <span class="pull-right" style="position: relative; bottom: 24px;">
	                    Expires: (@{{ card.exp_month}}/@{{ card.exp_year }})
	                    <label class="label label-primary" v-if="card.id == user.primary_card">Primary</label>
	                </span>
	            </span>
            </li>
        </ul>

        <span v-if="(addingNewCard || user.cards.length > 0) && ! readyForThreeDs">or <button class="btn btn-primary btn-xs" @click.prevent="addingNewCard = true">Add new Card</button></span>

        {{-- Add a new Credit Card to Account --}}
        <div v-if="(! forms.enroll.card || addingNewCard) && ! readyForThreeDs">
            <hr>

            <div class="form-group" :class="{ 'has-error': newcard.errors.number }">
                <label>Card Number</label>
                <input type="text" name="number" class="form-control" placeholder="4242 **** **** 4242" v-model="newcard.number" maxlength="16">
                <span class="help-block" v-if="newcard.errors.number">@{{ newcard.errors.number[0] }}</span>
            </div>

            <div class="form-group" :class="{ 'has-error': newcard.errors.holder }">
                <label>Card Holder</label>
                <input type="text" name="holder" class="form-control" placeholder="John Doe" v-model="newcard.holder">
                <span class="help-block" v-if="newcard.errors.holder">@{{ newcard.errors.holder[0] }}</span>
            </div>

            <div class="form-group" :class="{ 'has-error': newcard.errors.exp_month }">
                <div class="row">
                    <div class="col-md-5">
                        <label>Expiry Month</label>
                        <select name="exp_month" class="form-control" v-model="newcard.exp_month">
                            <option disabled selected>Select expiry month...</option>
                            <option value="01">01 - January</option>
                            <option value="02">02 - February</option>
                            <option value="03">03 - March</option>
                            <option value="04">04 - April</option>
                            <option value="05">05 - May</option>
                            <option value="06">06 - June</option>
                            <option value="07">07 - July</option>
                            <option value="08">08 - August</option>
                            <option value="09">09 - September</option>
                            <option value="10">10 - October</option>
                            <option value="11">11 - November</option>
                            <option value="12">12 - December</option>
                        </select>
                        <span class="help-block" v-if="newcard.errors.exp_month">@{{ newcard.errors.exp_month[0] }}</span>
                    </div>

                    <div class="col-md-5">
                        <label>Expiry Year</label>
                        <select name="exp_year" class="form-control" v-model="newcard.exp_year" :class="{ 'has-error': newcard.errors.exp_month }">
                            <option disabled selected>Select expiry year...</option>
                            @for($i = (int) date('Y'); $i < (int) date('Y') + 21; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                        <span class="help-block" v-if="newcard.errors.exp_year">@{{ newcard.errors.exp_year[0] }}</span>
                    </div>

                    <div class="col-md-2">
                        <label>Card CVV</label>
                        <input type="text" name="cvv" class="form-control" placeholder="123" v-model="newcard.cvv" :class="{ 'has-error': newcard.errors.cvv }">
                        <span class="help-block" v-if="newcard.errors.cvv">@{{ newcard.errors.cvv[0] }}</span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <button class="btn btn-primary" @click.prevent="addCard" :disabled="busy">
                    <span v-if="busy"><i class="fa fa-spinner fa-spin"></i> Saving Card...</span>
                    <span v-else>
	                	<i class="fa fa-save"></i> Save Card
	            	</span>
                </button>

                or
                <a class="text-danger" @click.prevent="addingNewCard = false">
                    Cancel and use existing card
                </a>

            </div>
        </div>

        <div v-if="readyForThreeDs">
            <div class="alert alert-success">
                <p>
                    <i class="fa fa-lock"></i> Your Credit Card is enrolled for 3DS Secure verification. In order for us to process your credit card, you are required to verify ownership of your credit card. In order to verify your ownership, click the <b>"Proceed to Verification"</b> button below. <a href="https://www.vcs.co.za/profile/3dsecure.asp" target="_blank" class="text-success">What is this ?</a>
                </p>
            </div>

            <form method="POST" :action="threeDs.url" style="margin-bottom: 0px;">
                <input type="hidden" name="connector" :value="threeDs.connector">
                <input type="hidden" name="MD" :value="threeDs.MD">
                <input type="hidden" name="TermUrl" :value="threeDs.TermUrl">
                <input type="hidden" name="PaReq" :value="threeDs.PaReq">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-lock"></i> Proceed to Verification
                </button>
            </form>
        </div>
    </div>
</div>

<div class="panel panel-default" v-if="forms.enroll.paymentOption == 'cc'">
    <div class="panel-body">
        <div class="form-group" :class="{'has-error': forms.enroll.errors.has('terms')}">
            <div class="col-sm-12">
                <span class="help-block" v-show="forms.enroll.errors.has('terms')">
                    <strong>@{{ forms.enroll.errors.get('terms') }}</strong>
                </span>
                <label class="checkbox nomargin">
                    <input type="checkbox" v-model="forms.enroll.terms">
                    <i></i>
                    I Accept The <a href="/terms_and_conditions" target="_blank">Terms Of Service</a>
                </label>
            </div>
        </div>
    </div>
</div>

<div class="row"  v-if="forms.enroll.paymentOption == 'cc'">
    <div class="col-md-12">
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block" @click.prevent="register" :disabled="forms.enroll.busy" v-if="user.cards.length > 0">
                <span v-if="forms.enroll.busy">
                    <i class="fa fa-btn fa-spinner fa-spin"></i> Registering
                </span>

                <span v-else>
                    <i class="fa fa-btn fa-check-circle"></i>

                    <span>
                        Complete Checkout
                    </span>
                </span>
            </button>

            <button type="button" class="btn btn-primary disabled btn-block" v-else>
				<span>
					<i class="fa fa-btn fa-check-circle"></i>

                    <span>
                        Complete Checkout
                    </span>
                </span>
            </button>
        </div>
    </div>
</div>