<?php
namespace Tests\Unit;

use App\Models\TVShow;
use DateTime;
use Tests\TestCase;
use Illuminate\Support\Collection;


class TVShowTest extends TestCase
{
    public function test_get_grouped_episodes_list()
    {
        // Arrange
        $show = new TVShow();
        $show->episodes = [
            [
                'season' => 1,
                'episode' => 1,
                'air_date' => '2023-01-01',
                'name' => 'Episode 1'
            ],
            [
                'season' => 1,
                'episode' => 2,
                'air_date' => '2023-01-08',
                'name' => 'Episode 2'
            ],
            [
                'season' => 2,
                'episode' => 1,
                'air_date' => '2023-02-01',
                'name' => 'Episode 3'
            ]
        ];

        // Act
        $result = $show->getGroupedEpisodesList();

        // Assert
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertEquals(2, $result->count()); // 2 seasons
        $this->assertArrayHasKey(1, $result); // Season 1 exists
        $this->assertArrayHasKey(2, $result); // Season 2 exists

        // Check season 1
        $season1 = $result[1];
        $this->assertEquals(2, $season1->count()); // 2 episodes
        $this->assertInstanceOf(\DateTime::class, $season1->start_date);
        $this->assertInstanceOf(DateTime::class, $season1->end_date);
        $this->assertEquals('2023-01-01', $season1->start_date->format('Y-m-d'));
        $this->assertEquals('2023-01-08', $season1->end_date->format('Y-m-d'));
        $this->assertInstanceOf(\stdClass::class, $season1->first());

        // Check season 2
        $season2 = $result[2];
        $this->assertEquals(1, $season2->count()); // 1 episode
        $this->assertInstanceOf(\stdClass::class, $season2->first());
    }
}
