<?php

namespace Larapen\Admin\PanelTraits;

trait Delete
{
    /*
    |--------------------------------------------------------------------------
    |                                   DELETE
    |--------------------------------------------------------------------------
    */

    /**
     * Delete a row from the database.
     *
     * @param  [int] The id of the item to be deleted.
     * @param int $id
     *
     * @return [bool] Deletion confirmation.
     *
     * TODO: should this delete items with relations to it too?
     */
    public function delete($id)
    {   



        if(isset($this->with_trashed) && $this->with_trashed){

            $data = $this->model->withTrashed()->find($id);
            $data->forceDelete();
            return $data;
        }
        else{
            return $this->model->destroy($id);

        }
    }
}
