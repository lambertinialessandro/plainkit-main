<?php
$site = site();
return [
    'title' => $page->title()->value(),
    'header' => [
        'headline' => $page->intro_headline()->or('FAQ')->value(),
        'text' => $page->intro_text()->value(),
    ],

    'questions' => $page->faq_list()->toStructure()->map(function ($item) {
        return [
            'question' => $item->question()->value(),
            'answer' => $item->answer()->value(),
        ];
    })->values(),
    'footerCta' => [
        'show' => $page->show_footer_cta()->toBool(),
        'title' => $site->global_cta_title()->value(),
        'text' => $site->global_cta_text()->value()
    ]
];

