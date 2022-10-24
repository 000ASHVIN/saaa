<?php
/**
 * Created by PhpStorm.
 * User: Tiaan Theunissen
 * Date: 4/20/2017
 * Time: 8:33 AM
 */

namespace App\Repositories\Sendinblue;
use Sendinblue\Mailin;
use SendinBlue\Client\Configuration;
use SendinBlue\Client\Api\ContactsApi;
use GuzzleHttp\Client;

class SendingblueRepository
{
    public function call()
    {
        $mailin = new Mailin('https://api.sendinblue.com/v2.0', env('SENDINBLUE_KEY'));
        return $mailin;
    }

    public function callv3()
    {
        $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', env('SENDINBLUE_APIKEY'));

        $apiInstance = new ContactsApi(
            new Client(),
            $config
        );
        return $apiInstance;
    }

    public function createSubscriber($subscriber, $listIds)
    {
        $key = env('APP_THEME') == 'taxfaculty'?'SMS':'CELL';
        $lastKey = env('APP_THEME') == 'taxfaculty'?'LASTNAME':'LAST_NAME';
        $data = [
            'email' => $subscriber['email'],
            'attributes' => [
                'FIRSTNAME' => $subscriber['first_name'],
                $lastKey => $subscriber['last_name'],
                $key => (isset($subscriber['cell']))?$subscriber['cell']:'',
            ],
            'listIds' => $listIds
        ];
        try {
            $result = $this->callv3()->createContact($data);
            return $result;
        } catch (\Exception $e) {
            return json_decode($e->getResponseBody(), true);
        }
    }

    public function removeCPDUserFromList($user)
    {
        // $data = [
        //     'email' => $user['email'],
        //     'listid_unlink' => [80]
        // ];
        $identifier = $user['email'];
        $data['unlinkListIds'] = [80];

        try {
            $result = $this->callv3()->updateContact($identifier, $data);
            return $result;
        } catch (\Exception $e) {
            return json_decode($e->getResponseBody(), true);
        }
    }

    public function createList($data) {
        try {
            $result = $this->callv3()->createList($data);
            return $result;
        } catch (\Exception $e) {
            return json_decode($e->getResponseBody(), true);
        }
    }

    public function getList($data) {
        try {
            $result = $this->callv3()->getList($data);
            return $result;
        } catch (\Exception $e) {
            return json_decode($e->getResponseBody(), true);
        }
    }

    public function getFolderLists($folderId) {
        try {
            $array = [];
            $limit = $this->callv3()->getFolderLists($folderId)['count'];
            $count = ($limit/50)>round($limit/50)? round($limit/50)+1:($limit/50);
            for($i= 0 ;$i<$count;$i++)
            {
                $offset = ($i)*50;
                $result = $this->callv3()->getFolderLists($folderId, 50,$offset);
               $array =  array_merge($array,$result['lists']);
                
            }
            return $array;
        } catch (\Exception $e) {
            return json_decode($e->getResponseBody(), true);
        }
    }

    public function getContactInfo($email) {
        try {
            $result = $this->callv3()->getContactInfo($email);
            return $result;
        } catch (\Exception $e) {
            return json_decode($e->getResponseBody(), true);
        }
    }

    public function updateContact($email, $data) {
        try {
            $result = $this->callv3()->updateContact($email, $data);
            return $result;
        } catch (\Exception $e) {
            return json_decode($e->getResponseBody(), true);
        }
    }

    public function getContactsFromList($listId, $modifiedSince = null, $limit = '50', $offset = '0') {
        try {
            $result = $this->callv3()->getContactsFromList($listId, $modifiedSince, $limit, $offset);
            return $result;
        } catch (\Exception $e) {
            return json_decode($e->getResponseBody(), true);
        }
    }

    public function createUpdateContact($data) {
        try {
            $contact = $this->callv3()->getContactInfo($data['email']);
            if(isset($contact['id'])) {
                $result = $this->callv3()->updateContact($data['email'], $data);
                return $result;
            }
        } catch (\Exception $e) {
            $error = json_decode($e->getResponseBody(), true);
            if($error['code'] == 'document_not_found') {
                
                try {
                    $result = $this->callv3()->createContact($data);
                    return $result;
                } catch (\Exception $e) {
                    return json_decode($e->getResponseBody(), true);
                }

            }

            return $error;
        }
    }
}