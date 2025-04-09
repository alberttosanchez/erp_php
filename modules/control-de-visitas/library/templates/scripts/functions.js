function get_time_duration( start_date , end_date ) {

    //console.log(start_date);
    //console.log(end_date);
        //start_date = '2022-04-05 16:03:50';
        let start = new Date( start_date.replace(/-/g, '/') );
        let end = new Date( end_date.replace(/-/g, '/') );
     
        let s1 = start.getTime();
        let s2 = end.getTime();
        let total = (s2 - s1)/1000;
        //console.log(total);
        let day = parseInt(total / (24*60*60));
        let afterDay = total - day*24*60*60;
        let hour = parseInt(afterDay/(60*60));
        let afterHour = total - day*24*60*60 - hour*60*60;
        let min = parseInt(afterHour/60);
        let afterMin = total - day*24*60*60 - hour*60*60 - min*60;
        let sec = parseInt((parseFloat(afterMin/60).toFixed(2))*100);
        let hous=(afterDay/3600).toFixed(2);

    /*console.log('day',day);
    console.log('afterDay',afterDay);
    console.log('hour',hour);
    console.log('afterHour',afterHour);
    console.log('min',min);
    console.log('afterMin',afterMin);
    console.log('hous',hous);
    console.log('sec',sec);
    console.log('hour',hour);*/

    let duration = `${day}:${hour}:${min}:${sec}`;

    //console.log(duration);
    return duration;

}