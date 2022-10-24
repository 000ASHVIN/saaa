<?php

use Illuminate\Database\Seeder;

class SponsorListTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('sponsor_list')->delete();
        
        \DB::table('sponsor_list')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'SAIBA',
                'email_id' => 'admin@auxilla.co.za',
                'content' => '<div style="background-color: white; padding: 10px; border: 1px solid #e3e3e3">
<div class="row">
<div class="col-md-12">
<h4>SAIBA - Southern African Institute for Business Accountants</h4>

<p>SAIBA is your gateway to the accounting profession. Join. Earn. Share.</p>

<p>SAIBA designations include:</p>

<table class="table">
<thead>
<tr>
<th>BA (SA)</th>
<th>BAP (SA)</th>
<th>CBA (SA)</th>
<th>CFO (SA)</th>
</tr>
</thead>
<tbody>
<tr>
<td>Step into your accounting career</td>
<td>Become an accountant in practice</td>
<td>Your gateway to becoming a superior financial manager</td>
<td>Become an international financial executive</td>
</tr>
</tbody>
</table>

<p><strong>Discount:</strong> Existing members of professional bodies:</p>

<p>SAAA subscribers that are members of professional bodies get exemptions to earn a SAIBA designation and a 50% discount on a designation fee.</p>

<p>Not yet a member of a professional body: SAAA subscribers get a SAIBA Associate membership for one year for free. Get to experience the SAIBA benefits at no cost.</p>

<p><strong>Steps:</strong></p>

<ol>
<li>Not yet a SAAA subscribers? Signup for a free CPD Subscription Package.</li>
<li>Login to your account and complete the form under rewards and SAIBA will contact you to explain how you can claim your discount.</li>
<li>SAIBA is a SAQA recognised controlling body for accountants with a statutory licence to issue designations for junior accountants, financial managers, CFOs and accountants in practice.</li>
</ol>
</div>
</div>
</div>
',
                'title' => 'SAIBA - Southern African Institute for Business Accountants',
                'is_active' => 1,
                'created_at' => '2019-06-07 11:31:28',
                'updated_at' => '2019-06-10 09:59:41',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'quickbooks',
                'email_id' => 'Bridget.Dutoit@quickbooks.co.za',
                'content' => '<div style="background-color: white; padding: 10px; border: 1px solid #e3e3e3">
<div class="row">
<div class="col-md-4">
<center><img alt="AON" class="thumbnail" src="/assets/frontend/images/sponsors/quickbooks.jpg" width="80%" /></center>
</div>

<div class="col-md-8">
<h4>QuickBooks for Accountants</h4>

<p>QuickBooks is the world&rsquo;s no. 1 cloud accounting solution, chosen by over 700k accounting partners around the world to manage and grow their practice.</p>

<p>How our product delivers:</p>

<ul>
<li><strong>Manage workflows effortlessly: </strong>Track clients and projects in one place from start to finish so nothing falls through the cracks.</li>
<li><strong>Collaborate in real-time: </strong>Access your client books anytime, anywhere to quickly answer questions. Set permissions within your team to control access to client data.</li>
<li><strong>Comply with complete confidence: </strong>Ensure that your clients&rsquo; books are done right with advanced audit trails, VAT compliance, and the ability to close prior periods.</li>
<li><strong>Grow your practice: </strong>Connect to local small businesses in need of help from experts like you.</li>
<li><strong>Get up to speed quickly: </strong>Free, flexible training and certification options plus unlimited phone and email support from QuickBooks experts will give you the confidence you need to fully support your clients.</li>
</ul>

<p>Not yet a QuickBooks Online for Accountant user?</p>

<ul>
<li><a href="https://quickbooks.intuit.com/za/accountants/?cid=SAAA_academy_co:za" target="_blank">Sign up</a> for QBOA for free</li>
<li>Get certified - attract new clients</li>
<li>Pass on 50% savings to your clients</li>
</ul>
</div>
</div>
</div>
',
                'title' => 'QuickBooks for Accountants',
                'is_active' => 1,
                'created_at' => '2019-06-07 12:00:00',
                'updated_at' => '2019-06-10 09:59:59',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'acts',
                'email_id' => 'chantal@firstforprofessionals.co.za, es.klue@gmail.com',
                'content' => '<div style="background-color: white; padding: 10px; border: 1px solid #e3e3e3">
<div class="row">
<div class="col-md-12">
<h4>Acts Online</h4>

<p>Acts Online provides legislation, including amendments and Regulations, in an intuitive, online format.</p>

<p>Acts Online is one of the leading resource for available Legislation in South Africa and are used daily by thousands of professionals and industry leaders.</p>

<p>With Acts you are guaranteed the latest and most up to date resource for your legislative needs. In addition Acts sells PDF copies of the legislation.</p>

<p><strong>Discount:</strong> Up to 25% discount for SAAA Subscribers on Acts Online memberships.</p>

<p><strong>Steps:</strong></p>

<ol>
<li>Not yet a SAAA subscribers? Signup for a free CPD Subscription Package.</li>
<li>Login to your account and complete the form under rewards and Acts Online will contact you to explain how you can claim your discount.</li>
</ol>
</div>
</div>
</div>
',
                'title' => 'ACTS Online',
                'is_active' => 1,
                'created_at' => '2019-06-07 12:01:25',
                'updated_at' => '2019-06-10 04:38:50',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'bluestar',
                'email_id' => 'chantal@firstforprofessionals.co.za, es.klue@gmail.com',
                'content' => '<div style="background-color: white; padding: 10px; border: 1px solid #e3e3e3">
<div class="row">
<div class="col-md-12">
<h4>First for BlueStar</h4>

<p>First For BlueStar is a committed team of financial planners situated in Constantia Kloof, Johannesburg. We are the only financial service provider in South Africa that focuses exclusively on recognised professionals. Our valued clients, who pass our Professional Vitality&trade; assessment, are entitled to rewards, practice support services, and tailor-made insurance solutions for their specific needs.</p>

<p>In addition to this, we also assist and enable professional firms to enter into the financial planning market without administrative burden and advice risk. We enable firms to diversify their revenue stream by facilitating the offering of financial advisory services to their clients.</p>

<p><strong>Insurance for professionals</strong></p>

<p>Reduced premiums based on your commitment to quality and professional conduct.</p>

<div class="col-md-4"><img alt="Sanlam" class="thumbnail" src="/assets/frontend/images/sponsors/sanlam.jpg" width="100%" /></div>

<div class="col-md-4"><img alt="Santam" class="thumbnail" src="/assets/frontend/images/sponsors/santam.jpg" width="100%" /></div>

<div class="col-md-4"><img alt="AON PI" class="thumbnail" src="/assets/frontend/images/sponsors/aon.jpg" width="100%" /></div>

<p><strong>Professional Indemnity Cover:</strong></p>

<p>According to the AON master policy, if you pay an estimated R399 per year you have access to either R2 000 000 or R5 000 000 to cover:</p>

<ul>
<li>Actual or alleged negligence and defence costs.</li>
<li>World wide claims.</li>
<li>Employee dishonesty.</li>
<li>Free Retroactive cover.</li>
<li>Thirty six (36) months post practice claims.</li>
<li>Subcontracted duties.</li>
<li>Computer Crime.</li>
<li>Public liability.</li>
<li>Commercial crime.</li>
<li>Directors&rsquo; &amp; Officers&rsquo; Liability.</li>
</ul>

<p><strong>Income protection</strong> - What happens if you fall ill or get injured, and cant practice?</p>

<p><strong>Retirement</strong> - Maintain your lifestyle during your retirement by saving over the long term.</p>

<p><strong>Short term insurance</strong> - personal and business asset risk solutions for car, household, hobby and specialist assets.</p>

<ol>
<li>Not yet a SAAA subscribers? Signup for a free CPD Subscription Package.</li>
<li>Login to your account and complete the form under rewards and a qualified FSCA accredited financial planner from First For BlueStar will contact you to explain how you can reduce your insurance cost by adhering to professional and quality control standards.</li>
</ol>
</div>
</div>
</div>
',
                'title' => 'First for BlueStar',
                'is_active' => 1,
                'created_at' => '2019-06-07 12:02:08',
                'updated_at' => '2019-06-10 04:38:38',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'draftworx',
                'email_id' => 'admin@auxilla.co.za',
                'content' => '<div style="background-color: white; padding: 10px; border: 1px solid #e3e3e3">
<h4>Automate your Financial Statements and Working Papers.</h4>

<p>More than 4 000 accounting firms can&rsquo;t be wrong. DRAFTWORX is flexible, affordable, easy to use and easy to learn.</p>

<p><strong>DRAFTWORX helps you complete financial statements for your clients and ensures you are compliant with:</strong></p>

<ul>
<li>IFRS for SME</li>
<li>IFRS</li>
<li>Modified cash basis of accounting</li>
<li>XBRL reporting to CIPC</li>
</ul>

<p><strong>You can also use DRAFTWORX to help you conduct an:</strong></p>

<ul>
<li>Audit</li>
<li>Independent review</li>
<li>Accounting officer engagements</li>
<li>Agreed upon procedures</li>
<li>Compilations</li>
</ul>

<p>DRAFTWORX provides full integration into all Sage Pastel, Quickbooks and Xero accounting products.</p>

<p><strong>Cost</strong></p>

<ul>
<li>Standard licence &ndash; <strong>R5 650</strong> annual fee.</li>
<li>Exclusive offer to SAAA members &ndash; <strong>R4 803</strong> for first annual licence fee.</li>
<li>XBRL tagging application for Excel financial statements &ndash; <strong>R1 250</strong>.</li>
</ul>

<p><strong>Steps to get your first DRAFTWORX licence at 15% discount.</strong></p>

<ul>
<li>Login to your profile on accountingacademy.co.za.</li>
<li>Click the Rewards tab and choose DRAFTWORX.</li>
<li>Have a look at the demo videos below.</li>
<li>Complete the form on the right.</li>
<li>You will receive a quote from DRAFTWORX.</li>
</ul>

<p><strong>Testimonial</strong></p>
<i>The program is just the best. Where it took me a day to do 2 financials on (censored product), I can now do 4 in a day and so easy to modify if the clients want to change something. Briliant! &ndash; Johan van Gass, VG Accountants</i>

<p><strong>EMAIL: </strong> admin@auxilla.co.za</p>

<p><strong>PHONE: </strong> Ronell 083 286 7350</p>

<p>DRAFTWORX offer only via SAAA and administered by <img alt="Logo" src="/assets/frontend/images/auxilla.png" width="20%" />.</p>
</div>

<hr />
<div class="col-md-6"><iframe allow="autoplay; encrypted-media" allowfullscreen="" frameborder="0" height="0" src="https://www.youtube.com/embed/A3GNSWEqTwY" style="min-height: 271px!important;" width="0"></iframe>

<hr /><iframe allow="autoplay; encrypted-media" allowfullscreen="" frameborder="0" height="0" src="https://www.youtube.com/embed/kVmUJdACbJc" style="min-height: 271px!important;" width="0"></iframe>

<hr /><iframe allow="autoplay; encrypted-media" allowfullscreen="" frameborder="0" height="0" src="https://www.youtube.com/embed/pcSM98U37gg" style="min-height: 271px!important;" width="0"></iframe></div>

<div class="col-md-6"><iframe allow="autoplay; encrypted-media" allowfullscreen="" frameborder="0" height="0" src="https://www.youtube.com/embed/fkIxyOHpgS4" style="min-height: 271px!important;" width="0"></iframe>

<hr /><iframe allow="autoplay; encrypted-media" allowfullscreen="" frameborder="0" height="0" src="https://www.youtube.com/embed/z03AX_ZLqJE" style="min-height: 271px!important;" width="0"></iframe></div>
',
                'title' => ' Draftworx Financials Statements & Working Papers',
                'is_active' => 1,
                'created_at' => '2019-06-07 12:13:48',
                'updated_at' => '2019-06-10 04:38:24',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'taxshop',
                'email_id' => 'chantal@firstforprofessionals.co.za, es.klue@gmail.com',
                'content' => '<div style="background-color: white; padding: 10px; border: 1px solid #e3e3e3">
<h3>Join the largest accounting and tax franchise in Southern Africa.</h3>

<p>Join our franchise to accelerate the performance of your accounting practice.</p>

<h4>Are you an accountant?</h4>

<p>Our focus is on establishing and expanding successful accounting and tax practices. The Tax Shop franchise is ideal for bookkeepers, accountants, auditors, tax professionals and related practitioners. It provides an excellent opportunity for new practices to establish themselves and for existing practices (no matter how small or large they may be) to expand. We require franchisees to have a formal qualification in the accounting/tax fields and preferably to be a member of a recognised professional body. We can also assist you to be registered with an approved professional body if you are not currently registered with one.</p>
&nbsp;

<h4>Who are we?</h4>

<p>We are the largest accounting and tax franchise in SA with more than 70 outlets operating in Southern Africa. We offer you the opportunity to benefit from the best forward-thinking accounting franchise brand with access to professional support, proven practice models, cutting edge software applications, latest marketing strategies and a huge network of accounting and tax professionals. The services our franchisees offer are in the accounting, payroll, tax and related fields and can be viewed in detail on our website <a href="http://www.taxshop.co.za/index.php?nav=services">here</a>.</p>
&nbsp;

<h4>What is the cost?</h4>

<p>The current cost for a new franchise licence is R250,000. <b>However, SAAA members receive a further 20% discount on this price - this equates to a massive R50,000 saving!</b> If you do not have finance available for the full amount please let us know and we will try our best to help you. For example, we may arrange a repayment period for part of the amount and/or may be able to assist you with obtaining funding from a financial institution.</p>
&nbsp;

<h4>What do we offer you?</h4>

<p>The cost referred to above includes the following:</p>

<ul>
<li>The right to trade in a zone agreed upon with you.</li>
<li>Start-up training at our head office (3 days in Pretoria) with all costs of transport, meals and accommodation included.</li>
<li>Twelve-month online international marketing course specifically designed for accountants. This course is offered by a world-renowned expert and has proved very successful in helping accountants to drive new business to their practices.</li>
<li>An initial marketing launch in your area e.g. newspapers adverts, social events, etc. The purpose of this is to ignite your practice locally in a big way and to announce the launch of your practice.</li>
<li>Marketing material in the form of banners, flyers, business cards, corporate folders and brochures designed to kickstart your practice.</li>
<li>Personal mentoring on running your practice including input on marketing strategies which have been tested and proven historically.</li>
<li>Start-up material in the form of instructional guides, handbooks and other reference information.</li>
<li>Ongoing technical support, training, backup and expertise from top professionals in the services offered by The Tax Shop including accounting, payroll, taxation, business consulting, assurance and legal and trust services.</li>
<li>Admission to world-class software operating in the cloud and which can be accessed from anywhere in the world. These applications assist our franchisees to deliver the best services in accounting, financial statement preparation, payroll services, etc.</li>
<li>Discounted access to professional organisations e.g. SAIBA, SAIT, etc.</li>
<li>Substantial discounts in many other areas. The size of our group makes it possible to negotiate such discounts. For example, professional indemnity insurance, which can cost tens of thousands of rands a year for a single practitioner will only cost a few hundred rands a year through us. Similarly, the cost of the online marketing course referred to above has been reduced from nearly R70,000 to less than R20,000 for our franchisees (the cost of this course is included in our franchise licence fee).</li>
<li>Unlimited email addresses and fax-to-email numbers.</li>
</ul>

<p>Please view our website <a href="http://www.taxshop.co.za/index.php?nav=franchising">here</a> for more information about our franchise opportunities including frequently asked questions. We also recommend that you download our franchise info <a href="https://www.taxshop.co.za/files/TaxShopFranchise_InfoPack.pdf">pack here</a> which will provide you with a clearer understanding of our franchise.</p>
&nbsp;

<h4>What to do next?</h4>

<p>Please email your CV to <a href="mailto:enquiries@taxshop.co.za">enquiries@taxshop.co.za</a>. Thereafter, we will arrange to contact you to discuss options available to you.</p>
&nbsp;

<h4>Want us to call you?</h4>

<p>If you have any further questions or would like to chat to us first, please request a call-back by emailing us at <a href="mailto:enquiries@taxshop.co.za">enquiries@taxshop.co.za</a> indicating your contact number, date and time suitable for calling you.</p>
&nbsp;

<h4>Testimonials</h4>

<p><i>&quot; We recently started our Tax Shop and are pleased to say that the franchisor is honest, open and ethical. They give excellent support and advice and do not think they are untouchable like other franchisors out there. &quot; <b>Ilze Wiid (Roodepoort West)</b> </i></p>

<p><i>&quot; I love running my own business and having the support of my franchisor makes the task so much easier. I enjoy the encouragement and their response to queries is immediate which assists me in providing a quick, efficient service to my clients. &quot; <b>Joan Cain (False Bay South)</b> </i></p>

<p><i>&quot; I have been a Tax Shop franchisee for over 8 years and always had the most amazing support from our Head Office. They always show efficiency, professionalism and an interest in helping franchisees to prosper. &quot; <b>Kim Kale (Lydenburg)</b> </i></p>
&nbsp;

<p><strong>EMAIL: </strong> <a href="mailto:enquiries@taxshop.co.za">enquiries@taxshop.co.za</a></p>

<p><strong>PHONE: </strong> 0861 370 220</p>
</div>

<hr />',
                'title' => 'The Tax Shop Accountants - Accounting & Tax Franchise',
                'is_active' => 1,
                'created_at' => '2019-06-10 04:17:08',
                'updated_at' => '2019-06-10 04:37:58',
            ),
        ));
        
        
    }
}
