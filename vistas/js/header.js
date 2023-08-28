//const { fromEvent, switchMap, of, catchError, http } = rxjs;
//const { map, filter, repeat, mergeAll, timeout } = rxjs.operators;

const { from, fromEvent, of } = rxjs;
const { debounceTime, mergeAll, repeat, tap } = rxjs.operators;

const timeoutDelay = 1000 * 60 * 45; // 5 minutos
const events = ['keypress', 'click', 'wheel', 'mousemove', 'ontouchstart'];
const $onInactive = from(events.map(e => fromEvent(document, e)))
    .pipe(
        tap(console.log("entra...!!!")),
        mergeAll(),
        debounceTime(timeoutDelay),
        repeat()
    );

$onInactive.subscribe(() => {
    fetch('vistas/modulos/salir.php', { method: 'POST' });
});