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

    public $count = 0;

    private static $db = [
        'SlideshareID'     => 'Int',
        'MixcloudURL'      => 'VarChar(511)',
        'YouTubeID'        => 'VarChar(127)',
        'BrainsharkID'     => 'VarChar(127)',
        'VidyardID'        => 'VarChar(127)',
        'VimeoURL'         => 'VarChar(255)',
        'VimeoID'          => 'VarChar(50)',
        'WistiaIdentifier' => 'Varchar(50)',
    ];

    /**
     * List of one-to-one relationships. {@link DataObject::$has_one}
     *
     * @var array
     */
    private static $has_one = [
        'Image' => 'Image'
    ];

    private static $indexes = [];


    private function TypeEnabled($type = false)
    {

        if (!is_array($this->owner->config()->enabled_oembed_fields)) return true;

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
            $Media['Image'] = UploadField::create('Image',
                'Image');
            $Media['Image']->setRightTitle(
                'You must first save this Media before adding the image');
        } else {
            $Media['Image'] = HiddenField::create('Image', '');
        }

        if ($this->TypeEnabled('Slideshare')) {
            $Media['SlideshareID'] = TextField::create('SlideshareID',
                '<span style="position:relative;">
	                <a href="http://Slideshare.com/upload" target="_blank" style="position:absolute;top:1.5em;white-space:nowrap;">Upload Slides</a>
	            </span>
	            Slideshare URL', $this->owner->SlideshareID ?: null);
            $Media['SlideshareID']->setRightTitle(
                'Include the Slideshare <strong>URL</strong> for slideshows.<br/>' .
                '<strong>After saving:</strong> If valid, this will automatically convert to the Slideshare ID. If not, it will change to "0".'
            //TO GET IT: <u>Drag</u> this <a href="javascript:alert(typeof slideshare_object == \'object\' ? slideshare_object.presentationId : \'Not on a Slideshare page!\');">[SlideshareID]</a> to your bookmarks, then click the button while viewing a Slideshare page.'
            );
        } else {
            $Media['SlideshareID'] = HiddenField::create('SlideshareID', '');
        }

        if ($this->TypeEnabled('YouTube')) {
            $Media['YouTubeID'] = TextField::create('YouTubeID',
                '<span style="position:relative;">
	                <a href="http://YouTube.com/upload" target="_blank" style="position:absolute;top:1.5em;white-space:nowrap;">Upload Video</a>
	            </span>
	            YouTube URL');
            $Media['YouTubeID']->setRightTitle(
                'Include the <strong>URL</strong> for the YouTube video<br/>' .
                '<strong>After saving:</strong> If valid, this will automatically convert to the YouTube ID. If not, it will disappear.'
            );
        } else {
            $Media['YouTubeID'] = HiddenField::create('YouTubeID', '');
        }


        if ($this->TypeEnabled('Vimeo')) {
            $Media['VimeoID'] = TextField::create('VimeoID', 'Vimeo URL');
            $Media['VimeoID']->setRightTitle(
                'Include the <strong>URL</strong> for the Vimeo video<br/>' .
                '<strong>After saving:</strong> If valid, this will automatically convert to the Vimeo ID. If not, it will disappear.'
            );
        } else {
            $Media['VimeoID'] = HiddenField::create('VimeoID', '');
        }

        if ($this->TypeEnabled('Brainshark')) {
            $Media['BrainsharkID'] = TextField::create('BrainsharkID',
                'Brainshark Presentation URL');
            $Media['BrainsharkID']->setRightTitle(
                'Include the <strong>URL</strong> for the Brainshark presentation<br/>' .
                '<strong>After saving:</strong> If likely valid, this will automatically convert to the Brainshark ID. If not, it will disappear.'
            );
        } else {
            $Media['BrainsharkID'] = HiddenField::create('BrainsharkID', '');
        }

        if ($this->TypeEnabled('Mixcloud')) {
            $Media['MixcloudURL'] = TextField::create('MixcloudURL',
                '<span style="position:relative;">
	                <a href="http://Mixcloud.com/upload" target="_blank" style="position:absolute;top:1.5em;white-space:nowrap;">Upload Audio</a>
	            </span>
	            Mixcloud URL');
            $Media['MixcloudURL']->setRightTitle(
                'Include the <strong>URL</strong> for a feed OR individual post.'
            );
        } else {
            $Media['MixcloudURL'] = HiddenField::create('MixcloudURL', '');
        }

        if ($this->TypeEnabled('Vidyard')) {
            $Media['VidyardID'] = TextField::create('VidyardID', 'Vidyard URL');
            $Media['VidyardID']->setRightTitle(
                'Include the <strong>URL</strong> for the Vidyard video<br/>' .
                '<strong>After saving:</strong> If valid, this will automatically convert to the Vidyard ID. If not, it will disappear.'
            );
        } else {
            $Media['VidyardID'] = HiddenField::create('VidyardID', '');
        }

        if ($this->TypeEnabled('Wistia')) {
            $Media['WistiaIdentifier'] = TextField::create('WistiaIdentifier',
                '<span style="position:relative;">
				<a href="http://wistia.com/" target="_blank" style="position:absolute;top:1.5em;white-space:nowrap;">Upload Video</a>
			</span>
			Wistia ID')->setRightTitle(
                'Include the <strong>ID</strong> for the Wistia video'
            );
        } else {
            $Media['WistiaIdentifier'] = HiddenField::create('WistiaIdentifier', '');
        }

        if ($TabName = $this->owner->config()->EmbeddedMediaTabName) {
            $fields->addFieldsToTab($TabName, $Media);
            if ($Title = $this->owner->config()->EmbeddedMediaTitle) {
                $fields->fieldByName($TabName)->setTitle($Title);
            }
        } else if ($this->owner instanceof SiteTree) {
            $MediaFields = new ToggleCompositeField('MediaFields', $this->owner->config()->EmbeddedMediaTitle ?: 'Embedded Media', $Media);
            $MediaFields->setStartClosed(true);
            $MediaFields->setHeadingLevel(2);
            $fields->insertBefore('Metadata', $MediaFields);
        } else {
            $fields->addFieldsToTab('Root.oEmbeddableFields', $Media);
        }
    }


    /**
     * SETTERS
     */


    /**
     * @param $ID string|int A slideshare ID or URL
     */
    function setSlideshareID($ID)
    {
        if (!is_numeric($ID)) $ID = oEmbeddableFields::SlideshareID(trim($ID));
        $this->owner->setField('SlideshareID', $ID);
    }

    /**
     * @param $URL string
     */
    function setMixcloudURL($URL)
    {
        $this->owner->setField('MixcloudURL', trim($URL));
    }


    /**
     * @param $ID string
     */
    function setYouTubeID($ID)
    {
        $this->owner->setField('YouTubeID', oEmbeddableFields::YouTubeID(trim($ID)));
    }


    /**
     * @param $ID string
     */
    function setVimeoID($ID)
    {
        $id = json_decode(oEmbeddableFields::VimeoID(trim($ID)));
        if ($id) $this->owner->setField('VimeoID', $id->video_id);

    }



    /**
     * @param $ID string
     */
    function setVidyardID($ID)
    {
        $this->owner->setField('VidyardID', oEmbeddableFields::VidyardID(trim($ID)));
    }

    /**
     * @param $ID string
     */
    function setBrainsharkID($ID)
    {
        $this->owner->setField('BrainsharkID', oEmbeddableFields::BrainsharkID(trim($ID)));
    }


    /**
     * GETTERS
     */


    /**
     * @return int
     */
    function getEmbedCount()
    {
        return count(array_filter([$this->owner->SlideshareID, $this->owner->MixcloudURL, $this->owner->YouTubeID, $this->owner->VidyardID, $this->owner->BrainsharkID, $this->owner->VimeoID]));
    }

    /**
     * @return string
     */
    function getSlideshareSource()
    {
        if (!$this->owner->SlideshareID) return '';

        return "//www.slideshare.net/slideshow/embed_code/" . $this->owner->SlideshareID;
    }


    /**
     * @return string
     */
    function getSlideshareEmbed()
    {
        if (!$this->owner->SlideshareID) return '';

        return "<iframe id='Slideshare-{$this->owner->SlideshareID}' src='{$this->owner->SlideshareSource}' frameborder='0' marginwidth='0' marginheight='0' scrolling='no' style='border:1px solid #CCC;border-width:1px 1px 0;margin-bottom:5px' allowfullscreen webkitallowfullscreen mozallowfullscreen></iframe>";
    }

    /**
     * @return string
     */
    function getMixcloudSlug()
    {
        $parts = explode('/', $this->owner->MixcloudURL);
        end($parts);

        return prev($parts);
    }

    /**
     * @return string
     */
    function getMixcloudSource()
    {
        if (!$this->owner->MixcloudURL) return '';
        $EncodedURL = rawurlencode($this->owner->MixcloudURL);

        return "//www.mixcloud.com/widget/iframe/?feed=" . $EncodedURL . "&embed_type=widget_standard";
    }

    /**
     * @return string
     */
    function getMixcloudEmbed()
    {
        if (!$this->owner->MixcloudURL) return '';

        return "<iframe id='Mixcloud-{$this->owner->MixcloudSlug}' src='{$this->owner->MixcloudSource}' frameborder='0'></iframe>";
    }


    function getYoutubeSource()
    {
        if (!$this->owner->YouTubeID) return '';

        return "https://www.youtube.com/embed/" . $this->owner->YouTubeID . "?rel=0";
    }

    /**
     * @return string
     */
    function getYouTubeEmbed()
    {
        if (!$this->owner->YouTubeID) return '';

        return "<iframe id='YouTube-{$this->owner->YouTubeID}' src='{$this->owner->YouTubeSource}' frameborder='0' allowfullscreen></iframe>";
    }

    /**
     * @return string
     */
    function getVidyardSource()
    {
        if (!$this->owner->VidyardID) return '';

        return "//micro.marketo.com/play.vidyard.com/" . $this->owner->VidyardID . ".html?v=3.1.1&type=inline&preload=none";
    }

    /**
     * @return string
     */
    function getVidyardEmbed()
    {
        if (!$this->owner->VidyardID) return '';

        return "<iframe id='Vidyard-{$this->owner->VidyardID}' src='{$this->owner->VidyardSource}' frameborder='0' allowfullscreen='1'></iframe>";
    }


    /**
     * @return string
     */
    function getBrainsharkSource()
    {
        if (!$this->owner->BrainsharkID) return '';

        return "https://www.brainshark.com/marketing-cloud/vu?pi=" . $this->owner->BrainsharkID . "&dm=5&pause=1&nrs=1";
    }

    /**
     * @return string
     */
    function getBrainsharkEmbed()
    {
        if (!$this->owner->BrainsharkID) return '';

        return "<iframe id='Brainshark-{$this->owner->BrainsharkID}' src='{$this->owner->BrainsharkSource}' frameborder='0' scrolling='no'></iframe>";
    }

    function getYouTubeImage()
    {
        if ($this->owner->YoutubeID) {
            return 'https://i.ytimg.com/vi/' . $this->owner->YoutubeID . '/hqdefault.jpg';
        }
    }

    function getVimeoEmbed()
    {
        if ($this->owner->VimeoID) {
            return '<iframe src="https://player.vimeo.com/video/' . $this->owner->VimeoID . '" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
        }
    }

    function getWistiaEmbed()
    {
        if ($this->owner->WistiaIdentifier) {
            return json_decode($this->WistiaIdentifier($this->owner->WistiaIdentifier))->html;
        }
    }

    /**
     * STATIC HELPERS
     */

    public static function SlideshareID($URL)
    {
        $json = @file_get_contents('http://www.slideshare.net/api/oembed/2?format=json&url=' . rawurlencode($URL));
        if (!$json) return '';
        $json = json_decode($json, true);
        if (!$json || !isset($json['slideshow_id']) || !$json['slideshow_id']) return '';

        return $json['slideshow_id'];
    }

    public function VimeoID($URL)
    {

        $json = @file_get_contents('http://vimeo.com/api/oembed.json?url=' . rawurlencode($URL));

        if (!$json)
            return null;

        if (trim($json) === '404 Not Found')
            return null;

        $validate_json = json_decode($json, true);
        if (!$validate_json || !isset($validate_json['video_id']) || !$validate_json['video_id']) return false;

        return $json;

    }

    public function WistiaIdentifier($ID)
    {

        $link = 'http://home.wistia.com/medias/'.rawurlencode($ID);
        ///https?:\/\/(.+)?(wistia.com|wi.st)\/(medias|embed)\/.*/

        $oembed_regex = '#https?://(.+)?(wistia\.com|wistia\.net|wi\.st)/(medias|embed)/(?:[\+~%\/\.\w\-]*)#';
        if ( preg_match( $oembed_regex, $link, $matches ) ) {
            $json = @file_get_contents('http://fast.wistia.com/oembed?url='.$link);

            if (!$json)
                return null;

            $validate_json = json_decode($json,true);
            if (!$validate_json || !isset($validate_json['thumbnail_url']) || !$validate_json['thumbnail_url']) return false;
            return $json;
        }
    }


    public static function YouTubeID($URL)
    {
        //TODO: Enable playlist embeds?

        //make sure they remembered to include the http, if it's a URL
        $http = (strpos($URL, 'http://') === 0 || strpos($URL, 'https://') === 0) ? null : 'http://';
        if ($parse = parse_url($http . $URL)) {
            //If the path doesn't exist, then it's likely that it's already the ID
            if (!isset($parse['path']) && count($parse) === 2) {
                $ID = $parse['host'];
            } else {
                if ($parse['host'] === 'www.youtu.be' || $parse['host'] === 'youtu.be') {
                    //Handle youtu.be URLs
                    $ID = str_replace('/', '', $parse['path']);

                } else if (strpos($parse['path'], '/embed/') === 0) {
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

        if (!$json)
            return null;

        if (trim($json) === '404 Not Found')
            return null;

        $json = json_decode($json, true);
        if (!$json || !isset($json['type']) || $json['type'] !== 'video') return null;


        return $ID;
    }

    public static function VidyardID($URL)
    {

        //make sure they remembered to include the http, if it's a URL
        $http = (strpos($URL, 'http://') === 0 || strpos($URL, 'https://') === 0) ? null : 'http://';
        if ($parse = parse_url($http . $URL)) {

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
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);

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

    public static function BrainsharkID($URL)
    {
        //make sure they remembered to include the http, if it's a URL
        $http = (strpos($URL, 'http://') === 0 || strpos($URL, 'https://') === 0) ? null : 'http://';
        if ($parse = parse_url($http . $URL)) {
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
