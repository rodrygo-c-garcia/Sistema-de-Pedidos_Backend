<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index()
    {
        $lista_productos = Producto::with('categoria')->get();
        return response()->json($lista_productos, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'stock' => 'required|numeric',
            'precio_compra' => 'required|numeric',
            'precio_venta' => 'required|numeric',
            'imagen' => 'required|image'
        ]);

        if (!$request->file('imagen')->isValid()) {
            return response()->json(['error' => 'Error al cargar el archivo de imagen'], 400);
        }
        $imagen = $request->file('imagen')->store('public/imagenes');
        $nombreArchivo = 'storage/imagenes/' . basename($imagen);

        $producto = new Producto();
        $producto->nombre = $request->nombre;
        $producto->cod_barras = $request->cod_barras;
        $producto->precio_compra = $request->precio_compra;
        $producto->precio_venta = $request->precio_venta;
        $producto->precio_venta = $request->precio_venta;
        $utilidad = $request->precio_venta - $request->precio_compra;
        $producto->utilidad = $utilidad;
        $producto->stock = $request->stock;
        $producto->imagen = $nombreArchivo;
        $producto->categoria_id = $request->categoria_id;
        $producto->save();

        return response()->json(['mensaje' => 'Producto Registrado', 'data' => $producto], 201);
    }

    public function update(Request $request, $id)
    {
        $producto = Producto::where('id', $id)->first();
        // Si existe el producto procedemos a guardar
        if ($producto) {
            $request->validate([
                'nombre' => 'required|required',
                'stock' => 'required|numeric',
                'precio_compra' => 'required|numeric',
                'precio_venta' => 'required|numeric',
                'imagen' => 'image|max:2048'
            ]);

            $producto->nombre = $request->nombre;
            $producto->cod_barras = $request->cod_barras;
            $producto->precio_compra = $request->precio_compra;
            $producto->precio_venta = $request->precio_venta;
            $utilidad = $request->precio_venta - $request->precio_compra;
            $producto->utilidad = $utilidad;
            $producto->stock = $request->stock;
            $producto->categoria_id = $request->categoria_id;

            if ($request->hasFile('imagen')) {
                // Si se envió una imagen, la guardamos en el servidor
                $producto->imagen = $request->file('imagen')->store('public/imagenes');
            }
            $producto->save();

            return response()->json(['mensaje' => 'Producto Modificado', 'data' => $producto], 201);
        }
        return response()->json(['mensaje' => 'El producto no existe'], 400);
    }

    public function destroy($id)
    {
        $producto = Producto::where('id', $id)->first();
        if ($producto) {
            $producto->delete();
            return response()->json(['mensaje' => 'Producto eliminado'], 204);
        }
        return response()->json(['mensaje' => 'El producto no se encuentra'], 400);
    }
}
