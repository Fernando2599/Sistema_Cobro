<div class="card">
    <div class="card-header">Agregar usuario</div>
    <div class="card-body">
                    
        <form action="" method="post">
          <div class="mb-3">
                <label for="" class="form-label">Nombre:</label>
                    <input
                        type="text"
                        class="form-control"
                        name="username"
                        id="username"
                        aria-describedby="helpId"
                        placeholder="Nombre de usuario"
                        required
                    />
                <div class="mb-3">
                    <label for="" class="form-label">Contraseña</label>
                    <div class="input-group">
                        <input
                            type="password"
                            class="form-control"
                            name="pass"
                            id="pass"
                            placeholder="Ingrese su contraseña"
                            required
                        />
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="" class="form-label">Rol</label>
                    <select
                        class="form-select form-select-lg"
                        name="rol"
                        id="rol"
                        required
                    >
                        <option value="" selected disabled>Seleccione un rol</option>
                        <option value="1">Cajero</option>
                        <option value="2">Administrador</option>
                    </select>
                </div>
                
                    <input
                        name=""
                        id=""
                        class="btn btn-success"
                        type="submit"
                        value="Agregar usuario"
                    />
                    <a href="?controlador=user&accion=inicio" class="btn btn-danger">Cancelar</a>
            
        </div>
            
    </form>
    </div>
</div>
<script src="public/js/toggle.js"></script>
<script src="public/js/user.js"></script>