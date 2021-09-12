<?php namespace App\Controllers\API;

use App\Models\ClienteModel;
use CodeIgniter\RESTful\ResourceController;

class Clientes extends ResourceController
{
    public function __construct(){
        $this->model = $this->setModel(new ClienteModel);
    }

    public function index(){
        $clientes = $this->model->findAll();
        return $this->respond($clientes);
    }

    public function create(){

        try {
            //recuperamos el cliente enviado por el usuario
            $cliente = $this->request->getJSON();

            //insertamos el cliente en la bbdd y lanzamos un error en caso de fallo
            if($this->model->insert($cliente)){
                //reenviamos el cliente con el id al usuario para confirmar la insercion
                $cliente->id = $this->model->insertID();
                return $this->respondCreated($cliente);
            }else{
                return $this->failValidationError($this->model->validation->listErrors());
            }

        } catch (\Exception $e) {
            return $this->failServeError('Error en el servidor');
        }
    }

    public function edit($id = null){
        try {
            //testeamos si la id es valida
            if($id == null){
                return $this->failValidationError('No se ha pasado en id valido');
            }

            //recuperamos el cliente
            $cliente = $this->model->find($id);

            //si el cliente no existe, retornamos un error
            if($cliente == null){
                return $this->failNotFound('No existe un cliente con la id ' .$id);
            }

            //retornamos la respuesta
            return $this->respond($cliente);

        } catch (\Exception $e) {
           return $this->failServeError('Error en el servidor');
        }
    }

    public function update($id = null){
        try {
            //testeamos si la id es valida
            if($id == null){
                return $this->failValidationError('No se ha pasado en id valido');
            }

            //recuperamos el cliente
            $clienteRecuperadoBBDD = $this->model->find($id);

            //si el cliente no existe, retornamos un error
            if($clienteRecuperadoBBDD == null){
                return $this->failNotFound('No existe un cliente con la id ' .$id);
            }

            //obtenemos los datos actualizados
            $cliente = $this->request->getJSON();

            //actualizamos la bbdd y lanzamos error en caso de fallo
            if($this->model->update($id, $cliente)){
                $cliente->id = $id;
                return $this->respondUpdated($cliente);
            }else{
                return $this->failValidationError($this->model->validation->listErrors());
            }
            //retornamos la respuesta
            return $this->respond($cliente);

        } catch (\Exception $e) {
           return $this->failServeError('Error en el servidor');
        }
    }

    public function delete($id = null){
        try {
            //testeamos si la id es valida
            if($id == null){
                return $this->failValidationError('No se ha pasado en id valido');
            }

            //recuperamos el cliente
            $clienteRecuperadoBBDD = $this->model->find($id);

            //si el cliente no existe, retornamos un error
            if($clienteRecuperadoBBDD == null){
                return $this->failNotFound('No existe un cliente con la id ' .$id);
            }


            //eliminamos el registro de la bbdd y lanzamos error en caso de fallo
            if($this->model->delete($id)){
                return $this->respondDeleted($clienteRecuperadoBBDD);
            }else{
                return $this->failServeError('No se ha podido eliminar el registro');
            }

            //retornamos la respuesta
            return $this->respond($cliente);

        } catch (\Exception $e) {
           return $this->failServeError('Error en el servidor');
        }
    }
}

?>