<?php

use App\Presenters\Presenter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class PresenterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        Presenter::create(
            [
                'name'      => 'Prof. Hentie van Wyk',
                'title'     => 'Programme Director for Accounting Education, University of the Free State',
                'company'   => 'South African Accounting Academy',
                'bio'       => '<p>
                                    Prof. van Wyk was born in Bloemfontein (South Africa) in 1956. He matriculated in 1974 and after
                                    national service he obtained a BCom degree in 1978. He became a Chartered Accountant (SA) in 1982
                                    after he served his trainee period at KPMG. He was promoted to Audit Manager and in 1984 left for
                                    the University of the Free State as a Senior Lecturer. He was promoted to Associate Professor in
                                    1988 and to Professor and Head of the Centre for Accounting in 1992. He obtained a PhD in Public
                                    Sector Management in 2003 and is currently the Programme Director for Accounting Education at the
                                    University of the Free State.
                                </p>

                                <p>
                                    He is currently lecturing on Advanced Financial Accounting to aspirant Chartered Accountants at
                                    postgraduate level. He is also involved in the MBA and MDP programmes of the Business School of the
                                    Faculty of Economic and Management Sciences. In addition, he presents update courses on a yearly
                                    basis to practising accountants countrywide.
                                </p>

                                <p>
                                    He is very involved in the affairs of the accounting profession. He is currently serving as a Board
                                    member of the Free State Society of Chartered Accountants and was President of the Society in 1994
                                    and 2009. He is a member of the Academic Review Committee of the South African Institute of
                                    Chartered Accountants. He also served on the Board and Exco of the South African Institute of
                                    Chartered Accountants for several years.
                                </p>

                                <p>
                                    He is a past President of the SA Accounting Association and the Vice President of the International
                                    Association of Accounting Education and Research. He is also extensively involved in the community.
                                    He is an Officer in his Church, served on the Management Body of Grey College and is the acting
                                    Chairperson of the Finance and Commercial Committee of Cricket South Africa.
                                </p>',
                'avatar'    => '/assets/frontend/images/presenters/hentievanwyk.jpg'
            ]
        );
        Presenter::create(
            [
                'name'  => 'Prof. Cobus Rossouw',
                'title'  => 'Associate Professor, University of the Free State',
                'company'  => '',
                'bio'  => '
                <p>
                    Prof. Rossouw was born in Bloemfontein, South Africa and obtained a BCompt. Hons degree in 1995.
                    Thereafter he completed his articles at the University of the Free State and PricewaterhouseCoopers
                    and became a Chartered Accountant (SA) in 1999. In 2000 he joined the University of the Free State
                    as a Senior Lecturer. He was promoted to an Associate Professor in 2006 after obtaining his Masters\'
                    degree in Financial Accounting (cum laude).
                </p>

                <p>
                    Cobus is currently lecturing on Advanced Financial Accounting to postgraduate students and
                    specialises in International Financial Reporting Standards (IFRSs). He is author and co-author of
                    various articles in academic and accounting literature, and author of various chapters in accounting
                    text books. He has presented a number of papers at international and national conferences. He also
                    presented a number of update courses to practising accountants.
                </p>

                <p>
                    He received the award for Top Achiever: Teaching and Learning (senior) in the Faculty of Economic
                    and Management Science in 2004.
                </p>
                ',
                'avatar' => '/assets/frontend/images/presenters/cobusrussouw.jpg'
            ]
        );
        Presenter::create(
            [
                'name'  => 'Chris van Dyk',
                'title'  => 'Attorney, Van Dyk and Horn Attorneys',
                'company'  => '',
                'bio'  => '

                <p>
                    Chris van Dyk has extensive experience with regards to commercial, civil and family law matters
                    which he deals with on a daily basis as part of his practice. He practices in the Magistrate and
                    High Courts and his knowledge base and experience is extensive and include areas of the law relating
                    to: criminal, contract, delict, insolvency, companies, trusts, property, personal injury, deceased
                    estates, labour, tax, medical negligence, ethics and insolvency. He has presented classes at both
                    the University of Pretoria and the Tshwane University of Technology.
                </p>

                <p>
                    Chris also acts as the legal advisor to a number of professional bodies and has successfully
                    defended many practitioners in court when faced with professional negligence claims.
                </p>',
                'avatar' => '/assets/frontend/images/presenters/chrisvandyk.jpg'
            ]
                );
        Presenter::create(
            [
                'name'  => 'Elise Waldeck',
                'title'  => 'Owner, Corporate Statutory Services',
                'company'  => '',
                'bio'  => '
                 <p>
                    Elise Waldeck completed a post-graduate diploma in Corporate Law during 2002 at UJ (RAU). She
                    attended Advanced Company Law I and II on the 2008 Companies Act by Prof. Michael Katz during 2012
                    at Wits. Her expertise lies with meeting administration, governance advisory and compliance in
                    accordance with King III and the Companies Act 71 of 2008.
                </p>

                <p>
                    She has vast knowledge and recognition of company statutory audits, CIPC preparation and liaison and
                    drafting special resolutions. Elise is a regular speaker at seminars on related topics. She is a
                    member of the IoDSA.
                </p>
                ',
                'avatar' => '/assets/frontend/images/presenters/elisewaldeck.jpg'
            ]
                );
        Presenter::create(
            [
                'name' => 'Liandi van Wyk',
                'title'  => 'Senior Lecturer in Financial Accounting, UNISA',
                'company'  => '',
                'bio' => '

                <p>
                    Liandi van Wyk is a qualified chartered accountant CA (SA) and senior lecturer in Financial
                    Accounting (CTA level) at UNISA, one of the South Africa\'s leading open distance learning
                    institutions and is passionate about financial accounting and her profession.
                    Liandi completed her studies at the University of the Free State and subsequently she completed one
                    year of academic articles at the University of the Free State as part of her SAICA training
                    contract.
                </p>

                <p>
                    Liandi passed her first qualifying exam (QE1) of SAICA with honours and was among the top 40
                    students in South Africa. She completed the remainder of her articles at PricewaterhouseCoopers
                    (PwC) specialising in agricultural corporations and technical accounting matters.
                </p>

                <p>
                    After her articles she worked in the Corporate Finance Advisory Division of PwC and assisted with
                    due diligence and valuation services. She also completed a six month secondment in the United States
                    assisting with valuations performed on venture capitals.
                    She joined her current institution in 2011 as a senior lecturer and is currently working towards a
                    Masters\' degree in Accounting Sciences.
                </p>
                ',
                'avatar' => '/assets/frontend/images/presenters/liandivanwyk.jpg'
            ]
        );
        Presenter::create(
        [
            'name' => 'Prof. Walter Geach',
            'title'  => 'Senior Professor, Department of Accounting, University of Cape Town',
            'company'  => '',
            'bio' => '
                <p>
                    Prof. Geach CA (SA) BA LLB (Cape Town) MCOM CTA FCIS is an admitted Advocate of the High Court and a
                    Chartered Accountant. He is the author of various publications on company law, close corporations,
                    financial and estate planning, and is the author of Trusts: Law and Practice published by Juta.
                </p>

                <p>
                    He has presented many seminars on trusts among other topics. He is currently a Professor in the
                    Accounting Department at the University of Cape Town. He is a Non-Executive Director of Grindrod
                    Limited.
                </p>',
            'avatar' => '/assets/frontend/images/presenters/walter.jpg'
        ]
    );
        Presenter::create(
        [
            'name' => 'Nicolaas van Wyk',
            'title'  => 'CEO, Southern African Institute of Business Accountants (SAIBA)',
            'company'  => '',
            'bio' => '
                <p>
                    Nicolaas van Wyk, BCom (Hon), MBA, Professional Accountant (SA), Business Accountant in Practice
                    (SA), is a well-known presenter and speaker and is currently the CEO of SAIBA and a director of the
                    SA Accounting Academy.
                </p>

                <p>
                    His employment history includes: Technical Executive, SAIPA, Head of Technical, ACCA South Africa
                    and strategy consultant to a number of professional bodies. He also maintains a private blog,
                    www.nicolaasvanwyk.wordpress.com, and edits a bi-weekly technical newsletter "Accounting Alert".
                </p>',
                        'avatar' => '/assets/frontend/images/presenters/nicolaasvanwyk.jpg'
                    ]
            );
        Presenter::create(
        [
            'name' => 'James Molfetas',
            'title'  => 'CEO, Infinite Sales Funnels',
            'company'  => '',
            'bio' => '
                <p>
                    James is a chartered accountant and has spent most of his life in the financial world as a financial
                    director in private and listed companies and also spent 10 years as an audit partner.
                </p>

                <p>
                    After being online since 2011, he finally found his "sweet spot" in the big bad world of Internet
                    marketing. He has been able to combine his entrepreneurial flair with his accounting, auditing and
                    consulting background by specializing in creating high converting sales funnels for different types
                    of business owners.
                </p>

                <p>
                    He helps businesses of all types and sizes generate leads online to build a large responsive email
                    list that can be marketed to on a regular basis, then turn these leads into customers and maximize
                    the lifetime value that businesses can expect from these customers.
                </p>

                <p>
                    He also has extensive product creation experience. He has created numerous products ranging from
                    teaching people how to make money from garage sales to YouTube marketing, Kindle creation and
                    marketing, affiliate marketing and membership sites. He has also published a book called: "Infinite
                    Sales Funnels: Online sales mastery ... more leads, more customers, more sales".
                </p>',
            'avatar' => '/assets/frontend/images/presenters/james.jpg'
        ]
    );Presenter::create(
                [
                    'name' => 'Dora du Plessis',
                    'title'  => 'Owner, Sekretari',
                    'company'  => '',
                    'bio' => '
                <p>
                    Dora du Plessis founded Sekretari (Pty) Ltd in 1984 and has since built the business into a
                    well-established company. Due to national growth, a second branch was established in Cape Town in
                    2013. She attended the Advanced Corporate Law course with Tshwane University of Technology (then
                    Pretoria Technikon) in 1999 and the Advanced Corporate Law and Securities Law at UNISA in 2013, as
                    well as various short seminars on the Companies Act No. 71 of 2008.
                </p>

                <p>
                    She has 30 years\' experience in the preparation of documents, acting as liaison with CIPC and
                    drafting of all documents concerningcompany formation and all changes to companies and close
                    corporations.
                </p>

                <p>
                    She acts as Company Secretary to various companies and has vast knowledge of company statutory
                    audits and the duties of a Company Secretary. She started her training career in 2010 and has
                    trained more than 1,000 delegates in the practical implementation of the Companies Act. She is also
                    a member of the IoDSA and is constantly working to update her knowledge on the Companies Act and the
                    procedures and filing methods at CIPC.
                </p>',
                    'avatar' => '/assets/frontend/images/presenters/dora.jpg'
                ]
            );Presenter::create(
                        [
                            'name' => 'George Kramvis',
                            'title'  => 'To be added.',
                            'company'  => '',
                            'bio' => '<p>
                                        George Kramvis has a B.Comm degree from the University of the Witwatersrand and has been in the
                                        Accounting field for over twenty years. For the last twelve years he has focused on educating
                                        professionals in the bookkeeping and accounting fields and has lectured for many of the top brands
                                        in education.
                                            </p>',
                            'avatar' => '/assets/frontend/images/presenters/george.jpg'
                        ]
                    );

        Presenter::create(
                    [
                        'name' => 'Ricardo Wyngaard',
                        'title'  => 'Attorney, Ricardo Wyngaard Attorneys',
                        'company'  => '',
                        'bio' => '<p>
                    Ricardo Wyngaard established a solo law firm under the name of Ricardo Wyngaard Attorneys (RWA), at
                    the beginning of June 2009. Ricardo completed his LLB degree during 1995 at the University of the
                    Western Cape in South Africa and his LLM at the University of Illinois in the USA during 2006.
                </p>

                <p>
                    Ricardo has been working with non-profit organisations since 1998. His focus on non-profit law
                    started when he was employed at the Legal Resources Centre\'s NPO Legal Support Project from 2000 to
                    2004.
                </p>

                <p>
                    He provided legal advice and assistance to NPOs, facilitated various training workshops, produced
                    booklets and co-authored various submissions on non-profit legislation. He joined the Non-Profit
                    Consortium during 2005 and remained there until June 2008 where he provided NPOs with assistance on
                    non-profit law and practices, conducted research and advocated and lobbied for legislative reform on
                    non-profit law.
                </p>

                <p>
                    He also got appointed as co-chairperson of the Working Committee on the non-profit company by the
                    Department of Trade & Industry as part of the company law reform process.
                </p>',
                        'avatar' => '/assets/frontend/images/presenters/ricardo.jpg'
                    ]
                );
        Presenter::create(
                    [
                        'name' => 'Mark Lloydbottom',
                        'title'  => 'Practice Management Consultant, UK',
                        'company'  => '',
                        'bio' => ' <p>
                    Mark Lloydbottom is acknowledged as a futurist who specialises in management planning and strategy
                    for accounting firms. His programmes and consulting are based on over 25 years\' experience as a
                    practitioner and consultant. He has worked with professional service firms in fifteen countries and
                    has lectured throughout Europe, North America and Africa, standing on platforms with leading
                    industry thinkers including David Maister and Paul Dunn. Mark was a practitioner for 16 years having
                    started his own practice in Bristol in 1978.
                </p>

                <p>
                    He is the founder of Practice Track and PracticeWEB and has also served on various committees with
                    the Institute of Chartered Accountants, including the 2005 Working Party. Mark was a non-executive
                    director of SWAT for ten years, serving as chairman for the last three. He has worked closely with
                    leading firms in the US including Practice Development Institute based in Chicago, BizActions in
                    Maryland, AccountingWEB in the UK and USA and Faust Management Corporation in San Diego. He lectures
                    throughout Europe, the United States and in South Africa for the South African Institute of
                    Chartered Accountants. He also works with a number of the UK\'s leading firm associations and
                    accounting institutes.
                </p>

                <p>
                    He is devoted to researching and identifying strategies to enable accounting firms to build the top
                    and bottom lines. He achieves this by delivering high-quality consulting, CD/DVD-based management
                    training programmes and lecturing. Yet he maintains his technical expertise and remains
                    PracticeWEB\'s principal author for site content used by more than 700 UK accounting firms.
                </p>

                <p>
                    Mark is the co-author of Clients4Life published by the Institute of Chartered Accountants of
                    Scotland, South African Institute of Chartered Accountants and the Institute of Chartered
                    Accountants of Australia and New Zealand. He is also the author of the CD/DVD/Manual Defining Edge,
                    Power UP Your Gross Margin.
                </p>',
                        'avatar' => '/assets/frontend/images/presenters/mark.jpg'
                    ]
                );

        Presenter::create(
                    [
                        'name' => 'Francis Cronje',
                        'title'  => 'MD, franciscronje.com and CEO at InfoSeal',
                        'company'  => '',
                        'bio' => ' <p>
                    Francis Cronje, MD at franciscronje.com and CEO at InfoSeal, is an Information Governance Specialist
                    with a strong legal background and years of practical experience, advising large entities on the
                    powerful impact of POPI. Apparently, his corporate clients refer to him as the "Cleaner". He
                    obtained a LLM degree from the University of Oslo\'s Norwegian Research Centre for Computers and Law,
                    specialising in Data Protection.
                </p>

                <p>
                    Francis has advised Parliament on POPI, where he served on the relevant portfolio, technical & NCOP
                    committees. He is the co-author of JUTA\'s popular book "POPI - 101 Q&As" and the co-author and
                    co-editor of Cyberlaw@SA. Francis is the IAPP\'s Chairperson in Cape Town and also serves as an
                    adjudicator and appeal panelist at WASPA and ISPA. He has written numerous articles and is a regular
                    speaker on POPI and ICT Law. He has also lectured at various SA universities and is an admitted
                    Advocate.
                </p>',
                        'avatar' => '/assets/frontend/images/presenters/francis.jpg'
                    ]
                );

        Presenter::create(
                    [
                        'name' => 'Jan Dijkman',
                        'title'  => 'Independent Legal and Ethics Consultant',
                        'company'  => '',
                        'bio' => '<p>
                    Jan Dijkman was the Project Director: Ethics and Discipline for the South African Institute of
                    Chartered Accountants for over 16 years. He has the
                    degrees of BA, LLB, LLM, and has Higher Diplomas in Tax and in Labour Law. He is a non-practising
                    Advocate of the High Court of South Africa
                    and is the 30th Certified Ethics Officer in South Africa.
                </p>

                <p>
                    He was the managing director of CAPIM until February 2015, a company he was involved with for more
                    than 17 years and which provides professional indemnity insurance to the Chartered Accountancy
                    profession. He was also on the Audit and Risk Committee, and the Social and Ethics Committee of a large non-listed South African company
                    (turnover in the region of R900 million) for the period 2010 to 2014.
                </p>

                <p>
                    He is an independent legal and ethics consultant, concentrating on business and professional ethics,
                    as well as corporate governance. Over the years, he
                    has conducted many training sessions on Ethics, particularly professional ethics as it relates to
                    the broader Accountancy and Tax professions, as well as
                    Business Ethics.
                </p>

                <p>
                    He also lectures at the University of Johannesburg / Institute of Director\'s diploma in corporate
                    governance, lecturing on directors\' responsibilities in
                    terms of the Companies Act, the common law and the King Code on Corporate Governance III.
                </p>',
                        'avatar' => '/assets/frontend/images/presenters/jan.jpg'
                    ]
                );

        Presenter::create(
                    [
                        'name' => 'Milan van Wyk',
                        'title'  => 'Lecturer, Financial Accounting, University of Johannesburg',
                        'company'  => '',
                        'bio' => '
                <p>
                    Milan van Wyk is a qualified Chartered Accountant CA(SA). He obtained his B.Acc degree (Cum Laude)
                    at the University of the Free State in 2007 and his B.Acc (Hons) degree in 2008. He did his trainee
                    contract through PwC and worked as a manager for another 18 months. Thereafter, he obtained
                    experience in the mining, construction and retail industries and he also has experience in
                    agriculture.
                </p>

                <p>
                    He has always had a passion for IFRS and education and in 2013 he joined the Department of
                    Accountancy at the University of Johannesburg where he is currently lecturing in accounting. He is
                    also involved in the JSE Proactive Financial Monitoring Project where financial statements of
                    companies listed on the JSE get reviewed on a rotational basis in a five-year cycle.
                </p>',
                        'avatar' => '/assets/frontend/images/presenters/milan.jpg'
                    ]
                );
        Model::reguard();
    }
}
