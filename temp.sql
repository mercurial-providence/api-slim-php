
    select 
    t1.ID,
    t1.TITLE,
    t1.DATE,
    t1.TECHNIQUE,
    t1.URL,
    t3.AUTHOR,
    t3.BORN_DIED,
    t5.FORM,
    t7.LOCATION,
    t9.SCHOOL,
    t11.TIMEFRAME,
    t13.TYPE

    from
    ART t1 inner join 

    ART_AUTHOR t2 on t1.id=t2.art_id inner join 
    AUTHOR t3 on t3.id=t2.author_id inner join

    ART_FORM t4 on t1.id=t4.art_id inner join 
    FORM t5 on t4.form_id=t5.id inner join 

    ART_LOCATION t6 on t1.id=t6.art_id inner join 
    LOCATION t7 on t6.location_id=t7.id inner join 

    ART_SCHOOL t8 on t1.id=t8.art_id inner join 
    SCHOOL t9 on t8.school_id=t9.id inner join 

    ART_TIMEFRAME t10 on t1.id=t10.art_id inner join 
    TIMEFRAME t11 on t10.timeframe_id=t11.id inner join 

    ART_TYPE t12 on t1.id=t12.art_id inner join 
    TYPE t13 on t12.type_id=t13.id; 


