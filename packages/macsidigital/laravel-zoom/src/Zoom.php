<?php

namespace MacsiDigital\Zoom;

class Zoom
{
    public function user() { return new ZoomUser(); }
    public function meeting() { return new ZoomMeeting(); }
}

class ZoomUser {
    public function where($key, $value) { return $this; }
    public function setPaginate($value) { return $this; }
    public function setPerPage($value) { return $this; }
    public function get() { return new class { public function toArray() { return ['data' => [['id' => 'me', 'type' => 1]]]; } }; }
    public function find($id) { return $this; }
    public function meetings() { return $this; }
    public function save($meeting) { return $meeting; }
}

class ZoomMeeting {
    public function make($data) { 
        $data['id'] = '12345678'; 
        $data['password'] = '123456';
        return (object)$data; 
    }
    public function find($id) { return $this; }
    public function toArray() { return []; }
}
