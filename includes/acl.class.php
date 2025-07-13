<?php
class ACLManager {
    private $aclPath;

    public function __construct($aclPath) {
        $this->aclPath = $aclPath;
    }

    public function getGroups() {
        $xml = simplexml_load_file($this->aclPath);
        $groups = [];
        foreach ($xml->group as $group) {
            $groupName = (string)$group['name'];
            $users = [];
            foreach ($group->object as $obj) {
                if (strpos((string)$obj, 'user.') === 0) {
                    $users[] = substr((string)$obj, 5);
                }
            }
            $groups[$groupName] = $users;
        }
        return $groups;
    }

    public function addUserToGroup($username, $groupName) {
        $xml = simplexml_load_file($this->aclPath);
        foreach ($xml->group as $group) {
            if ((string)$group['name'] === $groupName) {
                $object = $group->addChild('object', 'user.' . $username);
                $this->saveXML($xml);
                return true;
            }
        }
        return false;
    }

    public function removeUserFromGroup($username, $groupName) {
        $xml = simplexml_load_file($this->aclPath);
        foreach ($xml->group as $group) {
            if ((string)$group['name'] === $groupName) {
                foreach ($group->object as $index => $obj) {
                    if ((string)$obj === 'user.' . $username) {
                        unset($group->object[$index]);
                        $this->saveXML($xml);
                        return true;
                    }
                }
            }
        }
        return false;
    }

    private function saveXML($xml) {
        $xml->asXML($this->aclPath);
    }
}
?>
