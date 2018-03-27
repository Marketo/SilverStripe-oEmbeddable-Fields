<?php

/**
 * Easily add embeddable media to any DataObject or Page type
 *
 * TODO: cleanup CMS view
 *
 * @author Nathan J. Brauer
 */
class oEmbeddableFields extends DataExtension
{

    /**
     * @var int
     */
    public $count = 0;

    /**
     * @var array
     */
    private static $db = [
        'SlideshareID'     => 'Int',
        'MixcloudURL'      => 'VarChar(511)',
        'YouTubeID'        => 'VarChar(127)',
        'BrainsharkID'     => 'VarChar(127)',
        'VidyardID'        => 'VarChar(127)',
        'VimeoID'          => 'VarChar(50)',
        'WistiaIdentifier' => 'Varchar(50)',
        'SurveyMonkeyID'   => 'VarChar(127)',
    ];

    /**
     * List of one-to-one relationships. {@link DataObject::$has_one}
     *
     * @var array
     */
    private static $has_one = [
        'Image' => 'Image',
    ];

    /**
     * @var array
     */
    private static $indexes = [];

    /**
     * @var array
     */
    private static $summary_fields = [
        'EmbedList' => 'List of Embeds',
    ];

    /**
     * @param bool $type
     *
     * @return bool
     */
    private function TypeEnabled($type = false)
    {

        if (!is_array($this->owner->config()->enabled_oembed_fields)) {
            return true;
        }

        if (in_array($type, $this->owner->config()->enabled_oembed_fields)) {
            return true;
        }

        return false;
    }

    /**
     * @param \FieldList $fields
     */
    public function updateCMSFields(FieldList $fields)
    {

        $Media = [];

        if ($this->TypeEnabled('Image')) {
            $Media['Image'] = UploadField::create('Image', 'Image');
            $Media['Image']->setRightTitle('You must first save this Media before adding the image');
        } else {
            $Media['Image'] = HiddenField::create('Image', '');
        }

        if ($this->TypeEnabled('Slideshare')) {
            $Media['SlideshareID'] = TextField::create(
                'SlideshareID',
                '<span style="position:relative;">' .
                '<a href="http://Slideshare.com/upload" target="_blank" style="position:absolute;top:1.5em;white-space:nowrap;">Upload Slides</a>' .
                '</span>Slideshare URL',
                $this->owner->SlideshareID ?: null
            );
            $Media['SlideshareID']->setRightTitle(
                'Include the Slideshare <strong>URL</strong> or <strong>ID</strong> for slideshows.<br/>' .
                '<strong>After saving:</strong> If valid, this will automatically convert to the Slideshare ID. If not, it will change to "0".'
            //TO GET IT: <u>Drag</u> this <a href="javascript:alert(typeof slideshare_object == \'object\' ? slideshare_object.presentationId : \'Not on a Slideshare page!\');">[SlideshareID]</a> to your bookmarks, then click the button while viewing a Slideshare page.'
            );
        } else {
            $Media['SlideshareID'] = HiddenField::create('SlideshareID', '');
        }

        if ($this->TypeEnabled('YouTube')) {
            $Media['YouTubeID'] = TextField::create(
                'YouTubeID',
                '<span style="position:relative;">' .
                '<a href="http://YouTube.com/upload" target="_blank" style="position:absolute;top:1.5em;white-space:nowrap;">Upload Video</a>' .
                '</span>YouTube URL');
            $Media['YouTubeID']->setRightTitle(
                'Include the <strong>URL</strong> or <strong>ID</strong> for the YouTube video<br/>' .
                '<strong>After saving:</strong> If valid, this will automatically convert to the YouTube ID. If not, it will disappear.'
            );
        } else {
            $Media['YouTubeID'] = HiddenField::create('YouTubeID', '');
        }


        if ($this->TypeEnabled('Vimeo')) {
            $Media['VimeoID'] = TextField::create('VimeoID', 'Vimeo URL');
            $Media['VimeoID']->setRightTitle(
                'Include the <strong>URL</strong> or <strong>ID</strong> for the Vimeo video<br/>' .
                '<strong>After saving:</strong> If valid, this will automatically convert to the Vimeo ID. If not, it will disappear.'
            );
        } else {
            $Media['VimeoID'] = HiddenField::create('VimeoID', '');
        }

        if ($this->TypeEnabled('Brainshark')) {
            $Media['BrainsharkID'] = TextField::create('BrainsharkID', 'Brainshark Presentation URL');
            $Media['BrainsharkID']->setRightTitle(
                'Include the <strong>URL</strong> or <strong>ID</strong> for the Brainshark presentation<br/>' .
                '<strong>After saving:</strong> If likely valid, this will automatically convert to the Brainshark ID. If not, it will disappear.'
            );
        } else {
            $Media['BrainsharkID'] = HiddenField::create('BrainsharkID', '');
        }

        if ($this->TypeEnabled('Mixcloud')) {
            $Media['MixcloudURL'] = TextField::create(
                'MixcloudURL',
                '<span style="position:relative;">' .
                '<a href="http://Mixcloud.com/upload" target="_blank" style="position:absolute;top:1.5em;white-space:nowrap;">Upload Audio</a>' .
                '</span>Mixcloud URL'
            );
            $Media['MixcloudURL']->setRightTitle(
                'Include the <strong>URL ONLY</strong> for a feed OR individual post.'
            );
        } else {
            $Media['MixcloudURL'] = HiddenField::create('MixcloudURL', '');
        }

        if ($this->TypeEnabled('Vidyard')) {
            $Media['VidyardID'] = TextField::create('VidyardID', 'Vidyard URL');
            $Media['VidyardID']->setRightTitle(
                'Include the <strong>URL</strong> or <strong>ID</strong> for the Vidyard video<br/>' .
                '<strong>After saving:</strong> If valid, this will automatically convert to the Vidyard ID. If not, it will disappear.'
            );
        } else {
            $Media['VidyardID'] = HiddenField::create('VidyardID', '');
        }

        if ($this->TypeEnabled('Wistia')) {
            $Media['WistiaIdentifier'] = TextField::create(
                'WistiaIdentifier',
                '<span style="position:relative;">' .
                '<a href="http://wistia.com/" target="_blank" style="position:absolute;top:1.5em;white-space:nowrap;">Upload Video</a>' .
                '</span>Wistia URL'
            );
            $Media['WistiaIdentifier']->setRightTitle(
                'Include the <strong>URL</strong> or <strong>ID</strong> for the Wistia video<br/>' .
                '<strong>After saving:</strong> If valid, this will automatically convert to the Wistia ID. If not, it will disappear.'
            );
        } else {
            $Media['WistiaIdentifier'] = HiddenField::create('WistiaIdentifier', '');
        }

        if ($this->TypeEnabled('SurveyMonkey')) {
            $Media['SurveyMonkeyID'] = TextField::create('SurveyMonkeyID', 'SurveyMonkey ID');
            $Media['SurveyMonkeyID']->setRightTitle(
                'Include the <strong>ID ONLY</strong> for the SurveyMonkey page<br/>'
            );
        } else {
            $Media['SurveyMonkeyID'] = HiddenField::create('SurveyMonkeyID', '');
        }

        if ($TabName = $this->owner->config()->oembed_tab_name) {
            $fields->addFieldsToTab($TabName, $Media);
            if ($Title = $this->owner->config()->oembed_title) {
                $fields->fieldByName($TabName)->setTitle($Title);
            }
        } elseif ($this->owner instanceof SiteTree) {
            $MediaFields = new ToggleCompositeField('MediaFields', $this->owner->config()->oembed_title ?: 'Embedded Media', $Media);
            $MediaFields->setStartClosed(true);
            $MediaFields->setHeadingLevel(2);
            $fields->insertBefore('Metadata', $MediaFields);
        } else {
            $fields->addFieldsToTab('Root.EmbeddedMedia', $Media);
        }
    }


    /**
     * SETTERS
     */


    /**
     * @param string|int $ID A slideshare ID or URL
     */
    public function setSlideshareID($ID)
    {
        if (!is_numeric($ID)) {
            $ID = oEmbeddableFields::convert_to_id_slideshare(trim($ID));
        }
        $this->owner->setField('SlideshareID', $ID);
    }

    /**
     * @param string $URL
     */
    public function setMixcloudURL($URL)
    {
        $this->owner->setField('MixcloudURL', trim($URL));
    }


    /**
     * @param string $ID
     */
    public function setYouTubeID($ID)
    {
        $this->owner->setField('YouTubeID', oEmbeddableFields::convert_to_id_youtube(trim($ID)));
    }


    /**
     * @param string $ID
     */
    public function setVimeoID($ID)
    {
        $this->owner->setField('VimeoID', json_decode(oEmbeddableFields::convert_to_id_vimeo(trim($ID))));
    }


    /**
     * @param string $ID
     */
    public function setVidyardID($ID)
    {
        $this->owner->setField('VidyardID', oEmbeddableFields::convert_to_id_vidyard(trim($ID)));
    }

    /**
     * @param string $ID
     */
    public function setWistiaIdentifier($ID)
    {
        $this->owner->setField('WistiaIdentifier', oEmbeddableFields::convert_to_id_wistia(trim($ID)));
    }

    /**
     * @param string $ID
     */
    public function setBrainsharkID($ID)
    {
        $this->owner->setField('BrainsharkID', oEmbeddableFields::convert_to_id_brainshark(trim($ID)));
    }

    /**
     * TODO: Validate somehow :)
     *
     * @param string $ID
     */
    public function setSurveyMonkeyID($ID)
    {
        $this->owner->setField('SurveyMonkeyID', trim($ID));
    }


    /**
     * HELPERS
     */


    /**
     * Count of Valid Embeds
     *
     * @return int
     */
    public function getEmbedCount()
    {
        return count(array_filter(
            [
                $this->owner->SlideshareID,
                $this->owner->MixcloudURL,
                $this->owner->YouTubeID,
                $this->owner->VidyardID,
                $this->owner->BrainsharkID,
                $this->owner->VimeoID,
                $this->owner->WistiaIdentifier,
                $this->owner->SurveyMonkeyID,
            ]
        ));
    }

    /**
     * List of Valid Embed as IDs
     *
     * @return array
     */
    public function getValidEmbeds()
    {
        return array_filter([
            'Slideshare'   => $this->owner->SlideshareID,
            'Mixcloud'     => $this->owner->MixcloudURL,
            'YouTube'      => $this->owner->YouTubeID,
            'Vidyard'      => $this->owner->VidyardID,
            'Brainshark'   => $this->owner->BrainsharkID,
            'Vimeo'        => $this->owner->VimeoID,
            'Wistia'       => $this->owner->WistiaIdentifier,
            'SurveyMonkey' => $this->owner->SurveyMonkeyID,
        ]);
    }

    /**
     * List of Validated Embed Types
     *
     * @return string
     */
    public function getEmbedList()
    {
        return implode(', ', array_keys($this->getValidEmbeds()));
    }

    /**
     * List of Valid Embed as URLs
     *
     * @return array
     */
    public function getValidEmbedSources()
    {
        return array_filter([
            'Slideshare'   => $this->getSlideshareSource(),
            'Mixcloud'     => $this->getMixcloudSource(),
            'YouTube'      => $this->getYouTubeSource(),
            'Vidyard'      => $this->getVidyardSource(),
            'Brainshark'   => $this->getBrainsharkSource(),
            'Vimeo'        => $this->getVimeoSource(),
            'Wistia'       => $this->getWistiaSource(),
            'SurveyMonkey' => $this->getSurveyMonkeySource(),
        ]);
    }

    /**
     * List of Valid Embed as URLs
     *
     * @return string
     */
    public function getEmbedSourceList()
    {
        return implode(', ', $this->getValidEmbedSources());
    }

    /**
     * List of Valid Embeds as Embeds
     *
     * @return array
     */
    public function getEmbedValues()
    {
        return array_filter([
            'Slideshare'   => $this->getSlideshareEmbed(),
            'Mixcloud'     => $this->getMixcloudEmbed(),
            'YouTube'      => $this->getYouTubeEmbed(),
            'Vidyard'      => $this->getVidyardEmbed(),
            'Brainshark'   => $this->getBrainsharkEmbed(),
            'Vimeo'        => $this->getVimeoEmbed(),
            'Wistia'       => $this->getWistiaEmbed(),
            'SurveyMonkey' => $this->getSurveyMonkeyEmbed(),
        ]);
    }

    /**
     * List of Valid Embeds as Embeds
     *
     * @return ArrayList
     */
    public function getEmbeds()
    {
        $validEmbeds = $this->getEmbedValues();

        $list = ArrayList::create();

        if ($validEmbeds) {
            foreach ($validEmbeds as $type => $embed) {
                $list->push([
                    'Type'  => $type,
                    'Embed' => $embed,
                ]);
            }
        }

        return $list;
    }


    /**
     * GETTERS
     */


    /**
     * @return string URL
     */
    public function getSlideshareSource()
    {
        if (!$this->owner->SlideshareID) {
            return '';
        }

        return "https://www.slideshare.net/slideshow/embed_code/" . $this->owner->SlideshareID;
    }


    /**
     * @return string HTML iFrame
     */
    public function getSlideshareEmbed()
    {
        if (!$this->owner->SlideshareID) {
            return '';
        }

        return "<iframe id='Slideshare-{$this->owner->SlideshareID}' src='{$this->owner->SlideshareSource}' frameborder='0' marginwidth='0' marginheight='0' scrolling='no' style='border:1px solid #CCC;border-width:1px 1px 0;margin-bottom:5px' allowfullscreen webkitallowfullscreen mozallowfullscreen></iframe>";
    }

    /**
     * @return string
     */
    public function getMixcloudSlug()
    {
        $parts = explode('/', $this->owner->MixcloudURL);
        end($parts);

        return prev($parts);
    }

    /**
     * @return string URL
     */
    public function getMixcloudSource()
    {
        if (!$this->owner->MixcloudURL) {
            return '';
        }
        $EncodedURL = rawurlencode($this->owner->MixcloudURL);

        return "https://www.mixcloud.com/widget/iframe/?feed=" . $EncodedURL . "&embed_type=widget_standard";
    }

    /**
     * @return string HTML iFrame
     */
    public function getMixcloudEmbed()
    {
        if (!$this->owner->MixcloudURL) {
            return '';
        }

        return "<iframe id='Mixcloud-{$this->owner->MixcloudSlug}' src='{$this->owner->MixcloudSource}' frameborder='0'></iframe>";
    }

    /**
     * @return string URL
     */
    public function getYoutubeSource()
    {
        if (!$this->owner->YouTubeID) {
            return '';
        }

        return "https://www.youtube.com/embed/" . $this->owner->YouTubeID . "?rel=0";
    }

    /**
     * @return string HTML iFrame
     */
    public function getYouTubeEmbed()
    {
        if (!$this->owner->YouTubeID) {
            return '';
        }

        return "<iframe id='YouTube-{$this->owner->YouTubeID}' src='{$this->owner->YouTubeSource}' frameborder='0' allowfullscreen></iframe>";
    }

    /**
     * @return string URL
     */
    public function getYouTubeImage()
    {
        if ($this->owner->YoutubeID) {
            return 'https://i.ytimg.com/vi/' . $this->owner->YoutubeID . '/hqdefault.jpg';
        }
    }

    /**
     * @return string URL
     */
    public function getVidyardSource()
    {
        if (!$this->owner->VidyardID) {
            return '';
        }

        return "//play.vidyard.com/" . $this->owner->VidyardID . ".js?v=3.1.1&type=inline&preload=none";
    }

    /**
     * @return string HTML iFrame
     */
    public function getVidyardEmbed()
    {
        if (!$this->owner->VidyardID) {
            return '';
        }

        return "<script type='text/javascript' id='Vidyard-{$this->owner->VidyardID}' src='{$this->owner->VidyardSource}'></script>";
    }


    /**
     * @return string URL
     */
    public function getBrainsharkSource()
    {
        if (!$this->owner->BrainsharkID) {
            return '';
        }

        return "https://www.brainshark.com/marketing-cloud/vu?pi=" . $this->owner->BrainsharkID . "&dm=5&pause=1&nrs=1";
    }

    /**
     * @return string HTML iFrame
     */
    public function getBrainsharkEmbed()
    {
        if (!$this->owner->BrainsharkID) {
            return '';
        }

        return "<iframe id='Brainshark-{$this->owner->BrainsharkID}' src='{$this->owner->BrainsharkSource}' frameborder='0' scrolling='no'></iframe>";
    }

    /**
     * @return string URL
     */
    public function getVimeoSource()
    {
        if ($this->owner->VimeoID) {
            return 'https://player.vimeo.com/video/' . $this->owner->VimeoID;
        }
    }

    /**
     * @return string HTML iFrame
     */
    public function getVimeoEmbed()
    {
        if ($this->owner->VimeoID) {
            return '<iframe src="' . $this->owner->VimeoSource . '" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
        }
    }

    /**
     * @return string URL
     */
    public function getWistiaSource()
    {
        if ($this->owner->WistiaIdentifier) {
            return 'https://fast.wistia.net/embed/iframe/' . $this->owner->WistiaIdentifier;
        }
    }

    /**
     * @return string|mixed HTML iFrame
     */
    public function getWistiaEmbed()
    {
        if ($this->owner->WistiaIdentifier) {
            $json = $this->wistia_json($this->owner->WistiaIdentifier);
            if (!$json) {
                return '';
            }
            $json = json_decode($json, true);
            if (!$json || !isset($json['html']) || !$json['html']) {
                return '';
            }

            return $json['html'];
        }
    }

    /**
     * @return string|mixed URL
     */
    public function getWistiaImage()
    {
        if ($this->owner->WistiaIdentifier) {
            $json = $this->wistia_json($this->owner->WistiaIdentifier);
            if (!$json) {
                return '';
            }
            $json = json_decode($json, true);
            if (!$json || !isset($json['thumbnail_url']) || !$json['thumbnail_url']) {
                return false;
            }

            return $json['thumbnail_url'];
        }
    }

    /**
     * @return string URL
     */
    public function getSurveyMonkeySource()
    {
        if (!$this->owner->SurveyMonkeyID) {
            return '';
        }

        return "https://www.surveymonkey.com/r/" . $this->owner->SurveyMonkeyID;
    }

    /**
     * @return string HTML iFrame
     */
    public function getSurveyMonkeyEmbed()
    {
        if (!$this->owner->SurveyMonkeyID) {
            return '';
        }

        return "<iframe id='SurveyMonkey-{$this->owner->SurveyMonkeyID}' src='{$this->owner->SurveyMonkeySource}' frameborder='0' allowfullscreen='1'></iframe>";
    }

    /**
     *
     * STATIC HELPERS
     *
     */

    /**
     * parse_url with some pre-processing
     *
     * @param $URL
     * @return mixed
     */
    private static function parseUrl($URL)
    {
        //make sure they remembered to include the http, if it's a URL
        $http = (strpos($URL, 'http://') === 0 || strpos($URL, 'https://') === 0) ? null : 'http://';

        return parse_url($http . $URL);
    }

    /**
     * @param string $URL
     * @return mixed|null|string ID
     */
    public static function convert_to_id_slideshare($URL)
    {
        $json = @file_get_contents('http://www.slideshare.net/api/oembed/2?format=json&url=' . rawurlencode($URL));
        if (!$json) {
            return '';
        }
        $json = json_decode($json, true);
        if (!$json || !isset($json['slideshow_id']) || !$json['slideshow_id']) {
            return '';
        }

        return $json['slideshow_id'];
    }


    /**
     * @param string $URL
     *
     * @return bool|mixed|null ID
     */
    public static function convert_to_id_vimeo($URL)
    {
        $parse = oEmbeddableFields::parseUrl($URL);
        if ($parse) {
            //If the path doesn't exist, then it's likely that it's already the ID
            if (!isset($parse['path']) && count($parse) === 2) {
                $ID = $parse['host'];
            } else {
                //Handle vimeo video page URLs
                $ID = str_replace('/', '', $parse['path']);
            }
        } else {
            return null;
        }

        //validate the ID against Vimeo's oEmbed
        $json = @file_get_contents('http://vimeo.com/api/oembed.json?url=https://vimeo.com/' . $ID);

        if (!$json) {
            return null;
        }

        if (trim($json) === '404 Not Found') {
            return null;
        }

        $json = json_decode($json, true);
        if (!$json || !isset($json['video_id']) || !$json['video_id']) {
            return false;
        }

        return $ID;
    }

    /**
     * @param $URL
     *
     * @return mixed|null|string ID
     */
    public static function convert_to_id_wistia($URL)
    {
        $parse = oEmbeddableFields::parseUrl($URL);
        if ($parse) {
            //If the path doesn't exist, then it's likely that it's already the ID
            if (!isset($parse['path']) && count($parse) === 2) {
                $ID = $parse['host'];
            } else {
                //Handle Wistia video page URLs
                parse_str($parse['query'], $url_GET);
                if (isset($url_GET['wvideo'])) {
                    $ID = $url_GET['wvideo'];
                } else {
                    return null;
                }
            }
        } else {
            return null;
        }

        return $ID;
    }

    /**
     * @param $ID
     *
     * @return bool|null|string JSON
     */
    private static function wistia_json($ID)
    {
        $link = 'http://home.wistia.com/medias/' . rawurlencode($ID);

        $oembed_regex = '#https?://(.+)?(wistia\.com|wistia\.net|wi\.st)/(medias|embed)/(?:[\+~%\/\.\w\-]*)#';
        if (preg_match($oembed_regex, $link, $matches)) {
            $json = @file_get_contents('http://fast.wistia.com/oembed?url=' . $link);

            if (!$json) {
                return null;
            }

            $validate_json = json_decode($json, true);
            if (!$validate_json || !isset($validate_json['thumbnail_url']) || !$validate_json['thumbnail_url']) {
                return false;
            }

            return $json;
        }
    }


    /**
     * @param string $URL
     *
     * @return mixed|null|string ID
     */
    public static function convert_to_id_youtube($URL)
    {
        //TODO: Enable playlist embeds?

        $parse = oEmbeddableFields::parseUrl($URL);
        if ($parse) {
            //If the path doesn't exist, then it's likely that it's already the ID
            if (!isset($parse['path']) && count($parse) === 2) {
                $ID = $parse['host'];
            } else {
                if ($parse['host'] === 'www.youtu.be' || $parse['host'] === 'youtu.be') {
                    //Handle youtu.be URLs
                    $ID = str_replace('/', '', $parse['path']);

                } elseif (strpos($parse['path'], '/embed/') === 0) {
                    //Handle youtube embed URLs
                    $ID = str_replace('/embed/', '', $parse['path']);

                } else {
                    //Handle youtube video page URLs
                    parse_str($parse['query'], $url_GET);
                    if (isset($url_GET['v'])) {
                        $ID = $url_GET['v'];
                    } else {
                        return null;
                    }
                }
            }
        } else {
            return null;
        }

        //validate the ID against YouTube's oEmbed
        $json = @file_get_contents('https://www.youtube.com/oembed?format=json&url=http://youtu.be/' . $ID);

        if (!$json) {
            return null;
        }

        if (trim($json) === '404 Not Found') {
            return null;
        }

        $json = json_decode($json, true);
        if (!$json || !isset($json['type']) || $json['type'] !== 'video') {
            return null;
        }


        return $ID;
    }

    /**
     * @param string $URL
     *
     * @return mixed|null|string ID
     */
    public static function convert_to_id_vidyard($URL)
    {
        $parse = oEmbeddableFields::parseUrl($URL);
        if ($parse) {

            //If the path doesn't exist, then it's likely that it's already the ID
            if (!isset($parse['path']) && count($parse) === 2) {
                $ID = $parse['host'];
            } else {
                if ($parse['host'] === 'play.vidyard.com' || $parse['host'] === 'vidyard.com') {
                    $ID = str_replace(['/', '.html'], ['', ''], $parse['path']);
                } else {
                    //Handle youtube video page URLs
                    parse_str($parse['query'], $url_GET);
                    if (isset($url_GET['v'])) {
                        $ID = $url_GET['v'];
                    } else {
                        return null;
                    }
                }
            }
        } else {
            return null;
        }

        $url = 'http://play.vidyard.com/' . $ID . '.html?v=3.1&disable_ctas=1';

        $handle = curl_init($url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);

        /*$response = */
        curl_exec($handle);

        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        if ($httpCode == 404) {
            /* Handle 404 here. */
            $ID = '';
        }

        curl_close($handle);

        return $ID;
    }

    /**
     * @param string $URL
     *
     * @return mixed|null|string ID
     */
    public static function convert_to_id_brainshark($URL)
    {
        $parse = oEmbeddableFields::parseUrl($URL);
        if ($parse) {
            //If the path doesn't exist, then it's likely that it's already the ID
            if (!isset($parse['path']) && count($parse) === 2) {
                return $parse['host'];
            } else {
                parse_str($parse['query'], $url_GET);
                if (isset($url_GET['pi'])) {
                    return $url_GET['pi'];
                }
            }
        }

        return null;
    }

}
