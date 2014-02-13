<?php
namespace Giosh94mhz\GeonamesBundle;

/**
 *
 * @author Premi Giorgio <giosh94mz@gmail.com>
 */
final class GeonamesImportEvents
{
    const PRE_IMPORT = 'geonames.pre_import';

    const PRE_DOWNLOAD = 'geonames.import.pre_download';
    const ON_DOWNLOAD_PROGRESS = 'geonames.import.on_download_progress';
    const POST_DOWNLOAD = 'geonames.import.post_download';

    const PRE_IMPORT_STEP = 'geonames.import.step.pre_import';
    const ON_IMPORT_STEP_PROGRESS = 'geonames.import.step.on_import_progress';
    const POST_IMPORT_STEP = 'geonames.import.step.post_import';

    const POST_IMPORT = 'geonames.post_import';

    const ON_ERROR = 'geonames.on_error';
    const ON_SKIP = 'geonames.on_skip';
}
