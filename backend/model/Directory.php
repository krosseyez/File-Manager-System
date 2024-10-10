<?php
class Directory {
    private $db;

    public function __construct() {
        global $db;
        $this->db = $db;
    }

    public function getAllDirectories() {
        $stmt = $this->db->prepare("SELECT * FROM directories");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDirectoryById($id) {
        $stmt = $this->db->prepare("SELECT * FROM directories WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getSubDirectories($id) {
        $stmt = $this->db->prepare("SELECT * FROM directories WHERE parent_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFiles($id) {
        $stmt = $this->db->prepare("SELECT * FROM files WHERE directory_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createDirectory($data) {
        $stmt = $this->db->prepare("INSERT INTO directories (name, parent_id) VALUES (?, ?)");
        return $stmt->execute([$data['name'], $data['parent_id']]);
    }

    public function updateDirectory($id, $data) {
        $stmt = $this->db->prepare("UPDATE directories SET name = ? WHERE id = ?");
        return $stmt->execute([$data['name'], $id]);
    }

    public function deleteDirectory($id) {
        // Check if the directory is empty
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM files WHERE directory_id = ?");
        $stmt->execute([$id]);
        $fileCount = $stmt->fetchColumn();

        $stmt = $this->db->prepare("SELECT COUNT(*) FROM directories WHERE parent_id = ?");
        $stmt->execute([$id]);
        $subDirCount = $stmt->fetchColumn();

        if ($fileCount == 0 && $subDirCount == 0) {
            $stmt = $this->db->prepare("DELETE FROM directories WHERE id = ?");
            return $stmt->execute([$id]);
        }

        return false;
    }
}