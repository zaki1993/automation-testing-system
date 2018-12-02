use automation_testing_system;

# users
insert into User values('zaki', '81279', '$2y$10$qq5bFVlUtEW6q0biYLleg.pLlFHTxPD.1CFrKFlb4L.aMlfkZ9vca');

# roles
insert into Role values('admin');
insert into Role values('user');

# user roles
insert into User_Roles values('zaki', 'admin');

# languages
insert into Language values ('php');
insert into Language values ('java');