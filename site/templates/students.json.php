<?php
// site/templates/classes-page.json.php
$site = site();
$searchId = get('id');
$groupsStructure = $page->student_groups()->toStructure();

if ($searchId) {
    $group = $groupsStructure->findBy('class_id', $searchId);

    if ($group) {
        $imageFile = $group->class_image()->toFile();
        $years = $group->class_year()->toStructure()->first();

        $selectedProjectIds = $group->selected_projects()->split(',');
        $relatedProjectsData = [];
        $projectsPage = page('projects');
        if ($projectsPage) {
            $allProjects = $projectsPage->projects_list()->toStructure();

            foreach ($selectedProjectIds as $id) {
                $projectEntry = $allProjects->findBy('project_id', $id);

                if ($projectEntry) {
                    $projectImg = $projectEntry->header_image()->toFile();
                    $relatedProjectsData[] = [
                        'id' => $projectEntry->project_id()->value(),
                        'name' => $projectEntry->project_name()->value(),
                        'title' => $projectEntry->project_title()->value(),
                        'src' => $projectImg ? $projectImg->url() : null,
                        'categories' => $projectEntry->project_categories()->split(',')
                    ];
                }
            }
        }
        return [
            'id' => $group->class_id()->value(),
            'name' => $group->class_name()->value(),
            'descriptionLeft' => $group->class_description_left()->value(),
            'descriptionRight' => $group->class_description_right()->value(),
            'students' => $group->class_names()->value(),
            'src' => $imageFile ? $imageFile->url() : null,
            'relatedProjects' => $relatedProjectsData,
            'year' => [
                'start' => $years ? $years->start()->value() : null,
                'end' => $years ? $years->end()->value() : null,
            ],

        ];
    } else {
        return ['error' => 'Member not found'];
    }

} else {
    $groupsArray = [];
    $headerImg = $page->header_image()->toFile();

    foreach ($groupsStructure as $group) {
        $imageFile = $group->class_image()->toFile();
        $years = $group->class_year()->toStructure()->first();

        $groupsArray[] = [
            'id' => $group->class_id()->value(),
            'name' => $group->class_name()->value(),
            'src' => $imageFile ? $imageFile->url() : null,
            'year' => [
                'start' => $years ? $years->start()->value() : null,
                'end' => $years ? $years->end()->value() : null,
            ],
        ];
    }

    return [
        'intro' => [
            'headline' => $page->intro_headline()->value(),
            'text' => $page->intro_text()->value(),
            'image' => $headerImg ? $headerImg->url() : null
        ],
        'studentGroups' => $groupsArray,
        'footerCta' => [
            'show' => $page->show_footer_cta()->toBool(),
            'title' => $site->global_cta_title()->value(),
            'text' => $site->global_cta_text()->value()
        ]
    ];

}