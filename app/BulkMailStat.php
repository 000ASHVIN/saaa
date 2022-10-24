<?php

namespace App;

use App\AppEvents\Event;
use App\Http\Controllers\Admin\StatsController;
use DB;
use Illuminate\Database\Eloquent\Model;
use Schema;

class BulkMailStat extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    static function findByEmailTypeAndCampaignId($email, $type, $campaignId)
    {
        return static::where('email', $email)->where('type', $type)->where('campaign_id', $campaignId)->first();
    }

    public function scopeCampaign($query, $campaign)
    {
        return $query->where('campaign_id', $campaign);
    }

    public function scopeClicks($query)
    {
        return $query->where('type', 'clicks');
    }

    public function scopeOpens($query)
    {
        return $query->where('type', 'open');
    }

    //A very hacky way to emulate a relationship
    public static function scopeWithCampaignEvent($query)
    {
        $selects = [];

        $eventColumns = Schema::getColumnListing('events');
        foreach ($eventColumns as $eventColumn) {
            $selects[] = 'events.' . $eventColumn . ' as event.' . $eventColumn;
        }

        $bulkMailStatColumns = Schema::getColumnListing((new static)->getTable());
        foreach ($bulkMailStatColumns as $bulkMailStatColumn) {
            $selects[] = 'bulk_mail_stats.' . $bulkMailStatColumn;
        }

        $query->join('event_send_grid_campaign', 'bulk_mail_stats.campaign_id', '=', 'event_send_grid_campaign.campaign_id')
            ->join('events', 'event_send_grid_campaign.event_id', '=', 'events.id')->select($selects);
    }

    //A very hacky way to emulate a relationship
    public static function hydrate(array $items, $connection = null)
    {
        foreach ($items as $itemKey => $item) {
            foreach ($item as $attributeKey => $attribute) {
                array_set($items, $itemKey . '.' . $attributeKey, $attribute);
            }
        }
        return parent::hydrate($items, $connection);
    }
}