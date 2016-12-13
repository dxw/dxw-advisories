<?php
require_once 'wp-content/themes/dxw-advisories/lib/api/inspections_finder.class.php';

describe('\\DxwSec\\API\\InspectionsFinder', function() {
    beforeEach(function() {
        \WP_Mock::setUp();
        \Mockery::getConfiguration()->allowMockingNonExistentMethods(true);
    });

    afterEach(function() {
        \WP_Mock::tearDown();
        \Mockery::getConfiguration()->allowMockingNonExistentMethods(false);
    });

    describe('->find()', function() {
        it('returns an array of inspection objects', function() {
            $inspection = (object) [
                'ID' => 2644,
                'post_author' => 5,
                'post_date' => '2016-07-13 17:44:23',
                'post_date_gmt' => '2016-07-13 17:44:23',
                'post_content' => NULL,
                'post_title' => 'Advanced Custom Fields: Table Field',
                'post_excerpt' => NULL,
                'post_status' => 'publish',
                'comment_status' => 'closed',
                'ping_status' => 'closed',
                'post_password' => NULL,
                'post_name' => 'advanced-custom-fields-table-field',
                'to_ping' => NULL,
                'pinged' => NULL,
                'post_modified' => '2016-08-08 18:26:16',
                'post_modified_gmt' => '2016-08-08 18:26:16',
                'post_content_filtered' => NULL,
                'post_parent' => 0,
                'guid' => 'https://security.dxw.com/?post_type=plugins&#038;p=2644',
                'menu_order' => 0,
                'post_type' => 'plugins',
                'post_mime_type' => NULL,
                'comment_count' => 0,
                'filter' => 'raw',
            ];

            \WP_Mock::wpFunction('get_posts', [
                'return' => [$inspection],
            ]);

            \WP_Mock::wpFunction('get_permalink', [
                'args' => [2644],
                'return' => 'https://security.dxw.com/plugins/advanced-custom-fields-table-field',
            ]);

            \WP_Mock::wpFunction('get_field', [
                'args' => ['recommendation', 2644],
                'return' => 'green',
            ]);

            $finder = new \DxwSec\API\InspectionsFinder();
            $result = $finder->find('advanced-custom-fields-table-field');
            expect($result)->to->be->an('array');
            expect(count($result))->to->equal(1);

            $output = $result[0];
            expect($output->name)->to->equal('Advanced Custom Fields: Table Field');
            expect($output->slug)->to->equal('advanced-custom-fields-table-field');
            expect($output->date)->to->loosely->equal(date_create('2016-07-13 17:44:23'));
            expect($output->url())->to->equal('https://security.dxw.com/plugins/advanced-custom-fields-table-field');
            expect($output->result())->to->equal('No issues found');
        });

        it('searches the database for published inspections matching the slug', function() {
            $args = [
                'post_type' => 'plugins',
                'post_status' => 'publish',
                'meta_query' => [
                    'relation' => 'AND',
                    [
                        'key' => 'codex_link',
                        'value' => '/my-awesome-plugin/',
                        'compare' => 'LIKE',
                    ],
                ],
            ];

            \WP_Mock::wpFunction('get_posts', [
                'times' => 1,
                'args' => [$args],
                'return' => [],
            ]);
            $finder = new \DxwSec\API\InspectionsFinder();
            $finder->find('my-awesome-plugin');
        });

        context('when there are no matching inspections', function() {
            it('returns an empty array', function() {
                \WP_Mock::wpFunction('get_posts', [
                    'return' => []
                ]);
                $finder = new \DxwSec\API\InspectionsFinder();
                $result = $finder->find('slug-with-no-matches');
                expect($result)->to->equal([]);
            });
        });
    });
});
?>
