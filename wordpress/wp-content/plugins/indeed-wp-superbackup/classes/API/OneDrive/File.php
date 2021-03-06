<?php
class File extends Object
{
    /**
     * Constructor.
     *
     * @param Client       $client  The Client instance owning this Object
     *                              instance.
     * @param null|string  $id      The unique ID of the OneDrive object
     *                              referenced by this Object instance.
     * @param array|object $options An array/object with one or more of the
     *                              following keys/properties:
     *                                'parent_id'    (string) The unique ID of
     *                                                        the parent
     *                                                        OneDrive folder of
     *                                                        this object.
     *                                'name'         (string) The name of this
     *                                                        object.
     *                                'description'  (string) The description of
     *                                                        this object. May
     *                                                        be empty.
     *                                'size'         (int)    The size of this
     *                                                        object, in bytes.
     *                                'created_time' (string) The creation time,
     *                                                        as a RFC
     *                                                        date/time.
     *                                'updated_time' (string) The last
     *                                                        modification time,
     *                                                        as a RFC
     *                                                        date/time.
     *
     */
    public function __construct(Client $client, $id, $options = array())
    {
        parent::__construct($client, $id, $options);
    }

    // TODO: should somewhat return the content-type as well; this information
    // is not disclosed by OneDrive.
    /**
     * Fetches the content of the OneDrive file referenced by this File
     * instance.
     *
     * @param array $options Extra cURL options to apply.
     *
     * @return string The content of the OneDrive file referenced by this File
     *                instance.
     */
    public function fetchContent($options = array())
    {
        return $this->_client->apiGet($this->_id . '/content', $options);
    }

    /**
     * Copies the OneDrive file referenced by this File instance into another
     * OneDrive folder.
     *
     * @param null|string The unique ID of the OneDrive folder into which to
     *                    copy the OneDrive file referenced by this File
     *                    instance, or null to copy it in the OneDrive root
     *                    folder. Default: null.
     */
    public function copy($destinationId = null)
    {
        $this->_client->copyFile($this->_id, $destinationId);
    }
}
