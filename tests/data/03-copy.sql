insert into test_no_pk (title, data)
select title, data
from test_normal;

insert into test_string_pk (id_1, title, data)
select UUID(), title, data
from test_normal;

insert into test_composite_pk (id_1, id_2, title, data)
select UUID_SHORT(), UUID_SHORT(), title, data
from test_normal;
