<?php
class File {
    private $db;

    public function __construct() {
        global $db;
        $this->db = $db;
    }

    public function getAllFiles() {
        $stmt = $this->db->prepare("SELECT * FROM files");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFileById($id) {
        $stmt = $this->db->prepare("SELECT * FROM files WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createFile($data) {
        $stmt = $this->db->prepare("INSERT INTO files (name, directory_id, content) VALUES (?, ?, ?)");
        return $stmt->execute([$data['name'], $data['directory_id'], $data['content']]);
    }

    public function updateFile($id, $data) {
        $stmt = $this->db->prepare("UPDATE files SET name = ?, content = ? WHERE id = ?");
        return $stmt->execute([$data['name'], $data['content'], $id]);
    }

    public function deleteFile($id) {
        $stmt = $this->db->prepare("DELETE FROM files WHERE id = ?");
        return $stmt->execute([$id]);
    }
}