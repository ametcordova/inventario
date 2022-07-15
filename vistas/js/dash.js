const { fromEvent,switchMap, of, catchError, http } = rxjs;
const { fromFetch } = rxjs.fetch;
//const {http} = rxjs/utils;

//const { filter, map } = rxjs.operators;


    const btn$=document.documentElement.querySelectorAll('.change');

    const click$ = fromEvent(btn$, 'click');

//   const subscription = click$.subscribe({
//     next: (e) => console.log('Event :', e.path)

//   });

  const subscription1 = click$.subscribe({
    //next: (e) => console.log('Event0 :', e.path[0].dataset.stat)
    next: (e) => console.log('Event0 :', e.path[0].dataset.stat)
    
  });

  
    

  const data$ = fromFetch('https://api.github.com/users?per_page=5').pipe(
    switchMap(response => {
      if (response.ok) {
        // OK return data
        return response.json();
      } else {
        // Server is returning a status requiring the client to try something else.
        return of({ error: true, message: `Error ${ response.status }` });
      }
    }),
    catchError(err => {
      // Network or other error, handle appropriately
      console.error(err);
      return of({ error: true, message: err.message })
    })
  );
   
  data$.subscribe({
    next: result => console.log(result),
    complete: () => console.log('done')
  });