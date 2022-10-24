<?php

// Home Page
Breadcrumbs::register('home', function ($breadcrumbs) {
    $breadcrumbs->push('Home', route('home'));
});

Breadcrumbs::register('courses', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Online Courses', route('courses.index'));
});

Breadcrumbs::register('course.show', function ($breadcrumbs, $course) {
    $breadcrumbs->parent('courses');
    $breadcrumbs->push($course->title, route('courses.show', $course->reference));
});

// questionnaire
Breadcrumbs::register('questionnaire', function ($breadcrumbs) {
    $breadcrumbs->parent('competitions');
    $breadcrumbs->push('Questionnaire', route('questionnaire.index'));
});

Breadcrumbs::register('rewards', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Rewards', route('home'));
});

Breadcrumbs::register('news', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('News', route('news.index'));
});

Breadcrumbs::register('article', function ($breadcrumbs, $article) {
    $breadcrumbs->parent('news');
    $breadcrumbs->push($article->title, route('news.show'));
});

// Home Page
Breadcrumbs::register('competitions', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Competitions', route('competitions.index'));
});

// Instant EFT Results
Breadcrumbs::register('instant_eft', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Instant EFT Results', route('home'));
});

// Sponsors
Breadcrumbs::register('sponsors', function ($breadcrumbs) {
    $breadcrumbs->parent('About');
    $breadcrumbs->push('sponsors', route('sponsors.index'));
});

// SAIBA CPD
Breadcrumbs::register('saiba', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('CPD Subscription 2018', route('saiba.cpd.index'));
});


// PI Insurance
Breadcrumbs::register('insurance.index', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('PI Insurance', route('insurance.index'));
});

// Event Gallery
Breadcrumbs::register('gallery.index', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Event Gallery', route('gallery.index'));
});

// Event Gallery Show
Breadcrumbs::register('gallery.show', function ($breadcrumbs, $folder) {
    $breadcrumbs->parent('gallery.index');
    $breadcrumbs->push(ucfirst(str_replace('-', ' ', $folder)), route('gallery.show'));
});

// Test Drive System
Breadcrumbs::register('test_drive', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Test Drive System', route('cipc_test_dirve'));
});

// FAQ Section
Breadcrumbs::register('faq', function ($breadcrumbs) {
    $breadcrumbs->parent('About');
    $breadcrumbs->push('Frequently asked questions', route('faq'));
});

// General FAQ single question
Breadcrumbs::register('general_faq_question', function ($breadcrumbs, $faq) {
    $breadcrumbs->parent('faq');
    $breadcrumbs->push($faq->question);
});

//Privacy Policy
Breadcrumbs::register('privacy', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Privacy Policy', route('privacy_policy'));
});

//Terms and Conditions
Breadcrumbs::register('terms', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Terms and Conditions', route('terms_and_conditions'));
});

//About Page
Breadcrumbs::register('About', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('About', route('about'));
});

//About Page > Staff
Breadcrumbs::register('staff', function ($breadcrumbs) {
    $breadcrumbs->parent('About');
    $breadcrumbs->push('Our Team', route('staff'));
});

//About Page > Presenters
Breadcrumbs::register('Presenters', function ($breadcrumbs) {
    $breadcrumbs->parent('About');
    $breadcrumbs->push('Our Presenters', route('presenters'));
});

Breadcrumbs::register('presenter.show', function ($breadcrumbs, $presenter) {
    $breadcrumbs->parent('Presenters');
    $breadcrumbs->push($presenter, route('presenters.show'));
});

//About Page > CPD
Breadcrumbs::register('CPD', function ($breadcrumbs) {
    $breadcrumbs->parent('About');
    $breadcrumbs->push('CPD Subscription', route('cpd'));
});

// Professions
Breadcrumbs::register('profession', function ($breadcrumbs, $title) {
    $breadcrumbs->parent('CPD');
    $breadcrumbs->push(ucfirst($title), route('profession.show'));
});

//About Page > CPD > Cancellation
Breadcrumbs::register('cancel_cpd', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('CPD Cancellation', route('cancel_cpd'));
});

//About Page > CPD > confirm
Breadcrumbs::register('confirm_subscription', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Confirm Subscription', route('renew_subscription_post'));
});

//About Page > CPD > PI
Breadcrumbs::register('pi_insurance', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->parent('home');
    $breadcrumbs->push('PROFESSIONAL INDEMNITY', route('pi_insurance'));
});


//About Page > Partners
Breadcrumbs::register('Partners', function ($breadcrumbs) {
    $breadcrumbs->parent('About');
    $breadcrumbs->push('Our Partners', route('partners'));
});

//About Page > DraftWorx
Breadcrumbs::register('DraftWorx', function ($breadcrumbs) {
    $breadcrumbs->parent('Partners');
    $breadcrumbs->push('DraftWorx', route('partners/draftworx'));
});

//About Page > SAIBA
Breadcrumbs::register('SAIBA', function ($breadcrumbs) {
    $breadcrumbs->parent('Partners');
    $breadcrumbs->push('SAIBA', route('partners/saiba'));
});

//About Page > CPD
// Breadcrumbs::register('Partners', function ($breadcrumbs) {
//     $breadcrumbs->parent('About');
//     $breadcrumbs->push('Partners', route('partners'));
// });

//About Page > Careers
Breadcrumbs::register('careers', function ($breadcrumbs) {
    $breadcrumbs->parent('About');
    $breadcrumbs->push('Careers', route('careers'));
});

Breadcrumbs::register('jobs', function ($breadcrumbs) {
    $breadcrumbs->parent('careers');
    $breadcrumbs->push('Job Title', route('careers.show'));
});

//Event Main Page
Breadcrumbs::register('events', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('All Events', route('events.index'));
});

// Single Event Listing
Breadcrumbs::register('events.show', function ($breadcrumbs, $event) {
    $breadcrumbs->parent('events');
    $breadcrumbs->push($event, route('events.index'));
});

//Contact Us
Breadcrumbs::register('Contact', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Contact Us', route('contact'));
});

//Contact Us
Breadcrumbs::register('Donate', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Donate', route('donate'));
});

//Online Store
Breadcrumbs::register('store', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Online Store', route('store.index'));
});

Breadcrumbs::register('store.show', function ($breadcrumbs, $listing) {
    $breadcrumbs->parent('store');
    $breadcrumbs->push($listing, route('store.index'));
});

Breadcrumbs::register('store.checkout', function ($breadcrumbs) {
    $breadcrumbs->parent('store');
    $breadcrumbs->push('Checkout', route('store.index'));
});

Breadcrumbs::register('store.cart', function ($breadcrumbs) {
    $breadcrumbs->parent('store');
    $breadcrumbs->push('Shopping Cart', route('store.cart'));
});

Breadcrumbs::register('dashboard.tickets', function ($breadcrumbs) {
    $breadcrumbs->push('Tickets', route('dashboard.events'));
});

Breadcrumbs::register('tickets.links-and-resources', function ($breadcrumbs, $eventTitle, $venueTitle) {
    $breadcrumbs->parent('dashboard.tickets');
    $breadcrumbs->push($eventTitle . ' - ' . $venueTitle, route('dashboard.tickets.links-and-resources'));
});

Breadcrumbs::register('dashboard.cpd', function ($breadcrumbs) {
    $breadcrumbs->push('CPD', route('dashboard.cpd'));
});

Breadcrumbs::register('dashboard.cpd.show', function ($breadcrumbs, $cpd) {
    $breadcrumbs->parent('dashboard.cpd');
    $breadcrumbs->push($cpd->source, route('dashboard.cpd.certificate', [$cpd->id]));
});

Breadcrumbs::register('dashboard', function ($breadcrumbs) {
    $breadcrumbs->push('Dashboard', route('dashboard'));
});

Breadcrumbs::register('dashboard.my_webinars', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('My Webinars on Demand', route('dashboard.webinars_on_demand.index'));
});


Breadcrumbs::register('dashboard.invoices', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Invoices', route('dashboard.invoices'));
});

Breadcrumbs::register('dashboard.webinars', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Online Webinar', route('dashboard.webinars.watch'));
});

Breadcrumbs::register('invoices.settle', function ($breadcrumbs, $invoice) {
    $breadcrumbs->parent('dashboard.invoices');
    $breadcrumbs->push($invoice->reference, route('invoices.settle', [$invoice->id]));
});

/*
 * Webinars on Demand
 */
Breadcrumbs::register('webinars_on_demand_index', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Webinars On-Demand', route('webinars_on_demand.home'));
});

/*
 * Resource Centre
 */
Breadcrumbs::register('resource_centre', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Resource Centre', route('resource_centre.home'));
});

Breadcrumbs::register('technical_faqs', function ($breadcrumbs) {
    $breadcrumbs->parent('resource_centre');
    $breadcrumbs->push('Technical Faqs', route('resource_centre.technical_faqs.index'));
});

Breadcrumbs::register('legislation', function ($breadcrumbs) {
    $breadcrumbs->parent('resource_centre');
    $breadcrumbs->push('Legislation', route('resource_centre.legislation.index'));
});

Breadcrumbs::register('fund_a_learner', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Fund A Learner', route('courses.fund.learner'));
});