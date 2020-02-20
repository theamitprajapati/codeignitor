<?php

class Common_model extends CI_Model
{


    public  function get_client_ip()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }


    public function addActivities($title)
    {
        return $this->db->insert(TBL_ACTIVITIES, [
            'clientIP' => $this->get_client_ip(),
            'userID' => $this->session->userdata['logged_in']['_id'],
            'title' => str_replace('_', ' ', $title),
            'employee' => json_encode($this->session->userdata['logged_in']),
            'data' => json_encode($_REQUEST),
            'created' => date('Y-m-d H:i:s'),
        ]);
    }
    public function add($table, $inData)
    {
        $inData['created'] = date('Y-m-d H:i:s');
        $inData['updated'] = date('Y-m-d H:i:s');
        //$inData['userID'] = 1;//$this->session->userdata['userID'] in future
        $this->addActivities("New record insert in $table table");
        $this->db->insert($table, $inData);
        return $this->db->insert_id();
    }

    public function set($table, $where, $data)
    {
        $data['updated'] = date('Y-m-d H:i:s');
        $this->addActivities("update record $table table");
        return $this->db->where($where)->update($table, $data);
    }

    public function result($query)
    {
        return $query->result_array();
    }

    public function get($table, $select = '*')
    {
        // if (!empty($where))
        //     $this->db->where($where);
        $this->db->order_by("_id", "desc");
        return $this->result($this->db->select($select)->get($table));
    }

    public  function get_where($table, $where = '', $select = '*')
    {
        return $this->result($this->db->select($select)->get_where($table, $where));
    }

    public  function get_row($table, $where = '', $select = '*')
    {
        return $this->db->select($select)->get_where($table, $where)->row_array();
    }

    public function trans_start()
    {
        $this->db->trans_start(TRUE);
    }

    public function trans_complete()
    {
        $this->db->trans_complete();
    }

    public function trans_end()
    {
        if ($this->db->trans_status() === FALSE) {
            # Something went wrong.
            $this->db->trans_rollback();
            return FALSE;
        } else {
            # Everything is Perfect.
            # Committing data to the database.
            $this->db->trans_commit();
            return TRUE;
        }
    }
    public  function delete($table, $where)
    {
        $this->addActivities("Delete record $table table");
        return $this->db->where($where)->delete($table);
    }

    public function multiJoins($table, $select = '*')
    {

        /* Example how we can use
       @param  $select = 't1.name,t1.code,t2.name as status,t1.updated';
       @param  $table = [
                          'from' => TBL_BANK,
                          'where' =>['t1.statusID'=>1],
                          'join' => [
                              't2' => ['table' => TBL_STATUS, 'on' => 't1.statusID = t2._id']
                           ]
                        ];
        @param $select  can optional defuatl *               
        */
        $this->db->select($select);
        if (!isset($table['from'])) return false;

        if (isset($table['where']))
            $this->db->where($table['where']);

        if (isset($table['group']))
            $this->db->group_by($table['group']);

          if (isset($table['where_in'])) {
            foreach ($table['where_in'] as $key => $value)
                $this->db->where_in($key, $table['where_in'][$key]);
        }

        $this->db->order_by('t1._id', 'desc');
        $this->db->from($table['from'] . ' as t1');
        if (isset($table['join'])) {
            foreach ($table['join'] as $key => $join) {
                if (!empty($join)) {
                    if (isset($join['on']))
                        $this->db->join($join['table'] . " as $key ", $join['on'], 'left');
                }
            }
        }

        $query =  $this->db->get();
       // echo $this->db->last_query();die;
        return $query->result_array();
    }

    public function multiJoinsRow($table, $select = '*')
    {

        /* Example how we can use
       @param  $select = 't1.name,t1.code,t2.name as status,t1.updated';
       @param  $table = [
                          'from' => TBL_BANK,
                          'where' =>['t1.statusID'=>1],
                          'join' => [
                              't2' => ['table' => TBL_STATUS, 'on' => 't1.statusID = t2._id']
                           ]
                        ];
        @param $select  can optional defuatl *               
        */
        $this->db->select($select);
        if (!isset($table['from'])) return false;

        if (isset($table['where']))
            $this->db->where($table['where']);

        if (isset($table['group']))
            $this->db->group_by($table['group']);

       if (isset($table['where_in'])) {
            foreach ($table['where_in'] as $key => $value)
                $this->db->where_in($key, $table['where_in'][$key]);
        }

        $this->db->order_by('t1._id', 'desc');
        $this->db->from($table['from'] . ' as t1');
        if (isset($table['join'])) {
            foreach ($table['join'] as $key => $join) {
                if (!empty($join)) {
                    if (isset($join['on']))
                        $this->db->join($join['table'] . " as $key ", $join['on'], 'left');
                }
            }
        }

        $query =  $this->db->get();
        return $query->row_array();
    }
}