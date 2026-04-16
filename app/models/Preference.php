<?php
namespace App\Models;

class Preference {
    public $userId;
    public $environments;
    public $activities;

    public function __construct($userId, $environments, $activities) {
        $this->userId = $userId;
        $this->environments = $environments;
        $this->activities = $activities;
    }

    public function savePreferences($conn) {
        try {
            // start transaction
            $conn->beginTransaction();

            // delete existing preferences for this user
            $sql = "DELETE FROM user_environments WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$this->userId]);

            $sql = "DELETE FROM user_activities WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$this->userId]);

            // insert environments
            foreach ($this->environments as $environment) {
                $sql = "INSERT INTO user_environments (user_id, environment_name) VALUES (?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$this->userId, $environment]);
            }

            // insert activities
            foreach ($this->activities as $activity) {
                $sql = "INSERT INTO user_activities (user_id, activity_name) VALUES (?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$this->userId, $activity]);
            }

            // commit transaction
            $conn->commit();
            return true;
        } catch (\Exception $e) {
            $conn->rollBack();
            error_log('Preference save error: ' . $e->getMessage());
            error_log('User ID: ' . $this->userId);
            error_log('Environments: ' . print_r($this->environments, true));
            error_log('Activities: ' . print_r($this->activities, true));
            return false;
        }
    }
}