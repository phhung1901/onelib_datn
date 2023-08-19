<?php

namespace App\Service;

use App\Models\Enums\FigureType;
use App\Libs\FigureUrlHelper;
use App\Models\Document;
use App\DocumentProcess\FigureExporter\Entities\FigureData;

class FigureService
{
    /**
     * @param FigureData $figureResource
     * @param Document $document
     *
     * @return string
     */
    public function makeUrl(FigureData $figureResource, Document $document)
    {
        try {
            return FigureUrlHelper::thumb(
                $document->id,
                $figureResource->figure_position['p'],
                $figureResource->figure_position['w'],
                $figureResource->figure_position['xt'],
                $figureResource->figure_position['xb'],
                $figureResource->figure_position['yt'],
                $figureResource->figure_position['yb'],
                ($figureResource->slug ?: FigureType::search($figureResource->type)),
                'webp',
            );
        } catch (\Exception $ex) {
            return url('assets_v4/images/thumbnail-document.png');
        }
    }
}
