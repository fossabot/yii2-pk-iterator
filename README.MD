# Yii2 Primary Key Iterator

[![codecov](https://codecov.io/gh/serhioli/yii2-pk-iterator/branch/main/graph/badge.svg?token=BB5VQ7DZBY)](https://codecov.io/gh/serhioli/yii2-pk-iterator)

---

This component adds ability to iterate ActiveQuery result-set using between condition in SQL.

### Example:

- Let's suppose that we have some big query:
```sql
select *
from some_big_table
where 
      some_col_1 > 123
      and some_col_2 in (4, 5, 6)
;
```
- At first this Iterator will determine the primary-key column(ex. `id`)
- Than it will execute two queries
  - ```sql
    select min(id) from some_big_table where ...; # for example 3242
    ```
  - ```sql
    select max(id) from some_big_table where ...; # for example 86859
    ```
- After that, on each next step it will add batching condition like that(if we set `$batchSize = 1000`)
  - ```sql
    select min(id) from some_big_table where ... and id between 3242 and 4242;
    ```
  - ```sql
    select min(id) from some_big_table where ... and id between 4242 and 5242;
    ```
  - and so on..

### Limitations

- Besides that component is pretty simple and tested, you still need to keep in mind some edge cases:
  - Grouping queries aren't working with this algorithm
  - You may expi
