
SELECT p.`id_producto`,p.`cant`,p.`precio_compra`,p.`margen_utilidad`,p.`precio_venta`, e.fechaentrada, e.cantidad, s.fecha_salida,s.cantidad FROM `principal` p
LEFT JOIN hist_entrada e ON p.id_producto=e.id_producto
LEFT JOIN hist_salidas s ON p.id_producto=s.id_producto
where p.id_producto=32

SELECT p.`id_producto`,p.`cant`,p.`precio_compra`,p.`margen_utilidad`,p.`precio_venta`, e.fechaentrada, e.cantidad, s.fecha_salida,s.cantidad FROM `principal` p
LEFT JOIN hist_entrada e ON p.id_producto=e.id_producto
LEFT JOIN hist_salidas s ON p.id_producto=s.id_producto
GROUP BY e.fechaentrada, s.fecha_salida
HAVING p.id_producto=32

SELECT p.`id_producto`,p.`cant`,p.`precio_compra`,p.`margen_utilidad`,p.`precio_venta`, e.fechaentrada, e.cantidad, s.fecha_salida,s.cantidad, count(s.cantidad) AS cantsal FROM `principal` p
LEFT JOIN hist_entrada e ON p.id_producto=e.id_producto
LEFT JOIN hist_salidas s ON p.id_producto=s.id_producto
GROUP BY e.fechaentrada, s.fecha_salida
HAVING p.id_producto=32

CONSULTA TOTAL SALIDA POR DIA
SELECT `id_producto`,`fecha_salida`,sum(`cantidad`) FROM `hist_salidas` where `id_producto`=32 group by `fecha_salida`

// SCRIPT PARA SACAR EL REPORTE DE ENTRADAS POR ALMACEN, FAM, CAT PROD Y RANGO DE FECHA
SELECT he.id_producto, pro.id_familia, fa.familia, pro.id_categoria, cat.categoria, pro.codigointerno, pro.descripcion, pro.id_medida, me.medida, sum(he.cantidad) AS tot_entro, round(sum(he.cantidad*he.precio_compra),2) AS tot_compra FROM `hist_entrada` he
INNER JOIN productos pro ON pro.id=he.id_producto 
INNER JOIN familias fa ON fa.id=pro.id_familia
INNER JOIN categorias cat ON cat.id=pro.id_categoria
INNER JOIN medidas me ON me.id=pro.id_medida
WHERE he.id_almacen=1 AND pro.id_familia=1 AND pro.id_categoria=5 AND he.fechaentrada>='2019-05-01' AND he.fechaentrada<='2019-05-15' AND pro.id=22 GROUP by pro.id

SCRIPT PARA ACTUALIZAR CAMPO ventas de PRODUCTOS
UPDATE productos pro SET ventas =
     ( SELECT ifnull(sum( cantidad),0) total_calculado
		FROM hist_salidas hs
		WHERE hs.id_producto =pro.id)
		
CONSULTA PARA SABER LOS ULTIMOS MENOS VENDIDOS
SELECT * FROM `productos` GROUP by ventas order by ventas asc LIMIT 10


// 10 PRODUCTO MAS VENDIDOS
SELECT histsal.id_producto, SUM(histsal.cantidad) AS TotalVentas
FROM hist_salidas histsal
GROUP BY histsal.id_producto
ORDER BY SUM(histsal.cantidad) DESC
LIMIT 0 , 10

SELECT `fecha_salida`, `id_producto`, `cantidad`, `precio_venta`, `id_almacen` FROM `hist_salidas` WHERE `id_producto`=67

// totales de entradas y salidas
SELECT id, articulo,
     TOTAL_INGRESO,
	 TOTAL_SALIDA,
	 (TOTAL_INGRESO - TOTAL_SALIDA) AS TOTAL_DISPONIBLE
FROM   (SELECT a.descripcion AS ARTICULO, a.id, 
        (SELECT sum(he.cantidad) FROM hist_entrada he WHERE a.id=he.id_producto) AS TOTAL_INGRESO, 
        (SELECT sum(hs.cantidad) FROM hist_salidas hs WHERE a.id=hs.id_producto) AS TOTAL_SALIDA
		FROM productos a) QA

/cantidad de salida de un producto
SELECT histsal.id_producto, histsal.cantidad AS cantVentas, precio_venta, histsal.fecha_salida
FROM hist_salidas histsal
WHERE id_producto=67
ORDER BY histsal.fecha_salida ASC
LIMIT 0 , 12		

/* saber el stock de un productos, entradas mas salidas = stock*/
SELECT P.id,P.codigointerno, P.descripcion, IFNULL(E.cantidad, 0) entradas, IFNULL(S.cantidad, 0) salidas, IFNULL(E.cantidad, 0) - IFNULL(S.cantidad, 0) stock
    FROM productos P
    LEFT JOIN
    (SELECT id_producto, cantidad, SUM(cantidad) total_entradas FROM hist_entrada
    GROUP BY id_producto) E
    ON P.id = E.id_producto
    LEFT JOIN
    (SELECT id_producto, cantidad, SUM(cantidad) total_salidas FROM hist_salidas
    GROUP BY id_producto) S
    ON P.id = S.id_producto

 /*

 SELECT SUM(cantidad) AS entrada FROM `hist_entrada` WHERE `id_producto`=32 and `id_almacen`=1 and `fechaentrada`>='2020-01-01' AND `fechaentrada`<='2020-01-31'

 ACTUALIZA KARDEX SEGUN LAS ENTRADAS
 update kardex kd set kd.enero = (
  select sum(he.cantidad)
  from hist_entrada he WHERE he.`id_producto`=32 and he.`id_almacen`=1 and he.`fechaentrada`>='2020-01-01' AND he.`fechaentrada`<='2020-01-31') WHERE kd.id_producto=32;

  ACTUALIZA KARDEX SEGUN SALIDAS
  update kardex kd set kd.enero=kd.enero-(
  select sum(hs.cantidad)
  from hist_salidas hs WHERE hs.`id_producto`=32 and hs.`id_almacen`=1 and hs.`fecha_salida`>='2020-01-01' AND hs.`fecha_salida`<='2020-01-31') WHERE kd.id_producto=32;
  
  CONSULTA A LA TABLA DE AJUSTE DE INV POR ENTRADAS
  SELECT * FROM `ajusteinventario` WHERE `fecha_ajuste`>='2019-01-01' AND `fecha_ajuste`<='2019-12-31' AND `id_almacen`=1 AND `tipomov` IN (2,7,8)
 */   

/*
PARA SACAR VALORES UNICOS
SELECT * FROM hist_salidas GROUP BY `num_salida`;
*/