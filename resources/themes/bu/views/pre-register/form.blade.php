<div v-if="selected === 'option-one-bookkeeper-junior-accountant'">
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Accounting</th>
            <th>Tax</th>
        </tr>
        </thead>

        <tbody>
        <tr>
            <td>Accounting Cycle</td>
            <td>Taxation of small businesses</td>
        </tr>
        <tr>
            <td>Bookkeeping: From Trial Balance to Financial Statements</td>
            <td>Individual Tax Returns</td>
        </tr>
        <tr>
            <td></td>
            <td>Year-end Tax Update</td>
        </tr>
        <tr>
            <td></td>
            <td>Budget &amp; Tax Update</td>
        </tr>
        <tr>
            <td></td>
            <td>Employees &amp; Provisional Tax/Employees &amp; Payroll Withholding</td>
        </tr>
        </tbody>
    </table>

    <table class="table table-bordered">

        <tbody>
        <tr>
            <td>Monthly accounting update webinar</td>
        </tr>
        <tr>
            <td>Monthly tax update webinar</td>
        </tr>
        <tr>
            <td>Tax Administration Act Update webinar (once-off)</td>
        </tr>
        </tbody>
    </table>

    <table class="table table-bordered">
        <tfoot>
        <tr>
            <td colspan="2"><strong>Total CPD hours = 60</strong></td>
        </tr>
        </tfoot>
    </table>
</div>

<div v-if="selected === 'option-two-accounting-officer'">
    <table class="table table-bordered">
        <thead>
        <tr><th>Accounting</th>
            <th>Tax</th>
        </tr></thead>
        <tbody>
        <tr>
            <td>Accounting Officer Engagements</td>
            <td>Budget &amp; Tax Update</td>
        </tr>
        <tr>
            <td>CIPC Update</td>
            <td>Tax Administration Act/ SARS Objections, Appeals &amp; Dispute Resolution</td>
        </tr>
        <tr>
            <td>Trust Law Update</td>
            <td>Tax Planning for Trusts &amp; Deceased Estates</td>
        </tr>
        <tr>
            <td>Business Rescue</td>
            <td>Specialised VAT Issues</td>
        </tr>
        <tr>
            <td>IFRS for SMEs Update</td>
            <td>Entrepreneurial &amp; Medium Enterprises</td>
        </tr>
        <tr>
            <td>Preparing Financial Statements for IFRS for SMEs</td>
            <td>Year-end Tax Update</td>
        </tr>
        </tbody>
    </table>

    <table class="table table-bordered">
        <tbody>
        <tr>
            <td>Monthly accounting webinar (various topics)</td>
        </tr>
        <tr>
            <td>Monthly SARS and Tax Update webinar</td>
        </tr>
        <tr>
            <td>Monthly legislation update webinar</td>
        </tr>
        <tr>
            <td>Monthly practice management webinar</td>
        </tr>
        </tbody>
    </table>

    <table class="table table-bordered">
        <tfoot>
        <tr>
            <td colspan="2"><strong>Total CPD hours = 108</strong></td>
        </tr>
        </tfoot>
    </table>
</div>
<div v-if="selected === 'option-three-independent-reviewer'">
    <table class="table table-bordered">
        <thead>
        <tr><th>Accounting</th>
        </tr></thead>
        <tbody>
        <tr>
            <td>Independent Review Standard Update</td>
        </tr>
        <tr>
            <td>Preparing Working Papers for Independent Reviews</td>
        </tr>
        <tr>
            <td>IFRS for SMEs Update</td>
        </tr>
        <tr>
            <td>Preparing Financial Statements for IFRS for SMEs</td>
        </tr>
        </tbody>
    </table>

    <table class="table table-bordered">
        <tbody>
        <tr>
            <td>Monthly accounting webinar (various topics)</td>
        </tr>
        <tr>
            <td>Monthly legislation update webinar</td>
        </tr>
        <tr>
            <td>Monthly practice management webinar</td>
        </tr>
        </tbody>
    </table>

    <table class="table table-bordered">
        <tfoot>
        <tr>
            <td colspan="2"><strong>Total CPD hours = 56</strong></td>
        </tr>
        </tfoot>
    </table>
</div>

<div v-if="selected === 'option-comprehensive-accountancy-practice'">
    <p>Choose any eight of the below seminar topics</p>
    <div id="message"></div>
    <br>
    <table class="table table-bordered">
        <thead>
        <th>Accounting</th>
        <th>Tax</th>
        </thead>
        <tbody>
        <tr>
            <td><label><input onclick="return KeepCount()" type="checkbox" name="events[]" value="accounting-officer-engagements"> Accounting Officer Engagements</label></td>
            <td><label><input onclick="return KeepCount()" type="checkbox" name="events[]" value="budget-tax-update"> Budget & Tax Update</label></td>
        </tr>
        <tr>
            <td><label><input onclick="return KeepCount()" type="checkbox" name="events[]" value="cipc-update"> CIPC Update</label></td>
            <td><label><input onclick="return KeepCount()" type="checkbox" name="events[]" value="tax-administration-act-sars-objections-appeals-dispute-resolution"> Tax Administration Act/ SARS Objections, Appeals & Dispute Resolution</label></td>
        </tr>
        <tr>
            <td><label><input onclick="return KeepCount()" type="checkbox" name="events[]" value="trust-law-update"> Trust Law Update</label></td>
            <td><label><input onclick="return KeepCount()" type="checkbox" name="events[]" value="tax-planning-for-trusts-deceased-estates"> Tax Planning for Trusts & Deceased Estates</label></td>
        </tr>
        <tr>
            <td><label><input onclick="return KeepCount()" type="checkbox" name="events[]" value="business-rescue"> Business Rescue</label></td>
            <td><label><input onclick="return KeepCount()" type="checkbox" name="events[]" value="specialised-vat-issues"> Specialised VAT Issues</label></td>
        </tr>
        <tr>
            <td><label><input onclick="return KeepCount()" type="checkbox" name="events[]" value="ifrs-for-smes-update"> IFRS for SMEs Update</label></td>
            <td><label><input onclick="return KeepCount()" type="checkbox" name="events[]" value="entrepreneurial-medium-enterprises"> Entrepreneurial & Medium Enterprises</label></td>
        </tr>
        <tr>
            <td><label><input onclick="return KeepCount()" type="checkbox" name="events[]" value="preparing-financial-statements-for-ifrs-for-smes"> Preparing Financial Statements for IFRS for SMEs</label></td>
            <td><label><input onclick="return KeepCount()" type="checkbox" name="events[]" value="year-end-tax-update"> Year-end Tax Update</label></td>
        </tr>
        <tr>
            <td><label><input onclick="return KeepCount()" type="checkbox" name="events[]" value="accounting-cycle"> Accounting Cycle</label></td>
            <td><label><input onclick="return KeepCount()" type="checkbox" name="events[]" value="small-businesses"> Small businesses</label></td>
        </tr>
        <tr>
            <td><label><input onclick="return KeepCount()" type="checkbox" name="events[]" value="from-trial-balance-to-financial-statement"> From Trial Balance to Financial Statement</label></td>
            <td><label><input onclick="return KeepCount()" type="checkbox" name="events[]" value="employees-provisional-tax-employees-payroll-withholding"> Employees & Provisional Tax/Employees & Payroll Withholding</label></td>
        </tr>
        </tbody>
    </table>

    <table class="table table-bordered">
        <tbody>
        <tr>
            <td>Monthly accounting webinar (various topics)</td>
        </tr>
        <tr>
            <td>Monthly tax update webinar</td>
        </tr>
        <tr>
            <td>Monthly legislation update webinar</td>
        </tr>
        <tr>
            <td colspan="2">Monthly practice management webinar</td>
        </tr>
        </tbody>
    </table>

    <table class="table table-bordered">
        <tfoot>
        <tr>
            <td colspan="2"><strong>Total CPD hours = 92</strong></td>
        </tr>
        </tfoot>
    </table>
</div>