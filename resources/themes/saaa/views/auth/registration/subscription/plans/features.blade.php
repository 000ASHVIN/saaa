<div class="panel panel-default">
    <div class="panel-heading">
        Select Plan Topics

        <div class="pull-right">
            <h4 class="panel-title">(@{{ countSelectedFeatures }}/8)</h4>
        </div>
    </div>
    <div class="panel-body">
        
        <div class="form-group">
            <label>
                <input type="checkbox" value="accounting-officer-engagements" v-model="selectedFeatures" :disabled="! canSelectMoreFeatures"> Accounting Officer Engagements
            </label>
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" value="budget-tax-update" v-model="selectedFeatures" :disabled="! canSelectMoreFeatures"> Budget & Tax Update
            </label>
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" value="cipc-update" v-model="selectedFeatures" :disabled="! canSelectMoreFeatures"> CIPC Update
            </label>
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" value="tax-administration-act-sars-objections-appeals-dispute-resolution" v-model="selectedFeatures" :disabled="! canSelectMoreFeatures"> Tax Administration Act/ SARS Objections, Appeals & Dispute Resolution
            </label>
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" value="trust-law-update" v-model="selectedFeatures" :disabled="! canSelectMoreFeatures"> Trust Law Update
            </label>
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" value="tax-planning-for-trusts-deceased-estates" v-model="selectedFeatures" :disabled="! canSelectMoreFeatures"> Tax Planning for Trusts & Deceased Estates
            </label>
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" value="business-rescue" v-model="selectedFeatures" :disabled="! canSelectMoreFeatures"> Business Rescue
            </label>
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" value="specialised-vat-issues" v-model="selectedFeatures" :disabled="! canSelectMoreFeatures"> Specialised VAT Issues
            </label>
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" value="ifrs-for-smes-update" v-model="selectedFeatures" :disabled="! canSelectMoreFeatures"> IFRS for SMEs Update
            </label>
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" value="entrepreneurial-medium-enterprises" v-model="selectedFeatures" :disabled="! canSelectMoreFeatures"> Entrepreneurial & Medium Enterprises
            </label>
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" value="preparing-financial-statements-for-ifrs-for-smes" v-model="selectedFeatures" :disabled="! canSelectMoreFeatures"> Preparing Financial Statements for IFRS for SMEs
            </label>
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" value="year-end-tax-update" v-model="selectedFeatures" :disabled="! canSelectMoreFeatures"> Year-end Tax Update
            </label>
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" value="accounting-cycle" v-model="selectedFeatures" :disabled="! canSelectMoreFeatures"> Accounting Cycle
            </label>
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" value="small-businesses" v-model="selectedFeatures" :disabled="! canSelectMoreFeatures"> Small businesses
            </label>
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" value="from-trial-balance-to-financial-statement" v-model="selectedFeatures" :disabled="! canSelectMoreFeatures"> From Trial Balance to Financial Statement
            </label>
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" value="employees-provisional-tax-employees-payroll-withholding" v-model="selectedFeatures" :disabled="! canSelectMoreFeatures"> Employees & Provisional Tax/Employees & Payroll Withholding
            </label>
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" value="independent-review-standard-update" v-model="selectedFeatures" :disabled="! canSelectMoreFeatures"> Independent Review Standard Update
            </label>
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" value="preparing-working-papers-for-independent-reviews" v-model="selectedFeatures" :disabled="! canSelectMoreFeatures"> Preparing Working Papers for Independent Reviews
            </label>
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" value="bookkeeping-from-trial-balance-to-financial-statements" v-model="selectedFeatures" :disabled="! canSelectMoreFeatures"> Bookkeeping: From Trial Balance to Financial Statements
            </label>
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" value="individual-tax-returns" v-model="selectedFeatures" :disabled="! canSelectMoreFeatures"> Individual Tax Return
            </label>
        </div>

        <div class="form-group">
            <button class="btn btn-info" @click.prevent="clearSelectedFeatures()">Clear Selected</button>
        </div>        

    </div>
</div>