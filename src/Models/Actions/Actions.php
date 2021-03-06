<?php

namespace LKDev\HetznerCloud\Models\Actions;

use LKDev\HetznerCloud\HetznerAPIClient;
use LKDev\HetznerCloud\Models\Model;
use LKDev\HetznerCloud\Models\Servers\Server;
use LKDev\HetznerCloud\RequestOpts;

/**
 *
 */
class Actions extends Model
{
    /**
     * @var
     */
    public $actions;


    /**
     * @param RequestOpts $requestOpts
     * @return array
     * @throws \LKDev\HetznerCloud\APIException
     */
    public function all(RequestOpts $requestOpts): array
    {
        if ($requestOpts == null) {
            $requestOpts = new RequestOpts();
        }
        $response = $this->httpClient->get('actions' . $requestOpts->buildQuery());
        if (!HetznerAPIClient::hasError($response)) {
            $resp = json_decode((string)$response->getBody(), false);
            return self::parse($resp)->actions;
        }
    }

    /**
     * @param $actionId
     * @return \LKDev\HetznerCloud\Models\Actions\Action
     * @throws \LKDev\HetznerCloud\APIException
     */
    public function get($actionId): Action
    {
        return (new Action($this->httpClient))->getById($actionId);
    }

    /**
     * @param  $input
     * @return $this
     */
    public function setAdditionalData($input)
    {
        $this->actions = collect($input->actions)->map(function ($action, $key) {
            return Action::parse($action);
        })->toArray();

        return $this;
    }

    /**
     * @param $input
     * @param Server $server
     * @return $this|static
     */
    public static function parse($input)
    {
        return (new self($input->server))->setAdditionalData($input);
    }

    /**
     * Wait for an action to complete.
     *
     * @param Action $action
     * @param float $pollingInterval in seconds
     * @return bool
     * @throws \LKDev\HetznerCloud\APIException
     */
    public static function waitActionCompleted(Action $action, $pollingInterval = 0.5)
    {
        while ($action->status == 'running') {
            usleep($pollingInterval * 1000000);
            $action = $action->refresh();
        }
        return $action->status == 'success';
    }
}