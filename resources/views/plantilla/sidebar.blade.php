<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="nav">
            <li class="nav-title">
                Menu Opciones
            </li>

            <?php use App\Http\Controllers\AdmSessionController;
                $vent=AdmSessionController::listarPermisos();            
           
            ?>
            @if ($vent['modulos']=="baneado")
            <li>Cuenta sin acceso</li>
            @else
            @foreach ($vent['modulos'] as $item)
                <li class="nav-item nav-dropdown menudown ">
                    <a class="nav-link nav-dropdown-toggle" href="#"><i class="{{ $item->nombre_icono}}" style="color:white;"></i>   
                        <span style="color: turquoise; text-transform: capitalize;">
                            {{ $item->nombre }}
                        </span>
                      <!--  <font color="turquoise" style="text-transform:capitalize">{{ $item->nombre }}</font> -->
                    </a>
                    <ul class="nav-dropdown-items">
                        @foreach($item->ventanas as $ventana)
                            {{-- {{$ventana->idmodulo}} --}}
                            {{-- {{$item->id}} --}}
                            @if ($ventana->idmodulo == $item->id)
                                <li @click=" menu={{ $ventana->codventana }} "  class="nav-item">
                                    <a class="nav-link" href="#"><i class="fa fa-check"></i>{{ $ventana->nombre }}</a>
                                </li>
                                
                            @endif
                        @endforeach
                    </ul>
                </li>
            @endforeach
            @endif
            
        </ul>
    </nav>

    <button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div>
