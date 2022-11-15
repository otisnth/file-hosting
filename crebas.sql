/*==============================================================*/
/* Table: disciplines                                           */
/*==============================================================*/
create table disciplines (
   disciplines_id       SERIAL not null,
   disciplines_name     VARCHAR(255)         not null,
   constraint PK_DISCIPLINES primary key (disciplines_id)
);

/*==============================================================*/
/* Index: disciplines_PK                                        */
/*==============================================================*/
create unique index disciplines_PK on disciplines (
disciplines_id
);

/*==============================================================*/
/* Table: files                                                 */
/*==============================================================*/
create table files (
   files_id             SERIAL not null,
   posts_id             INT4                 not null,
   files_path           VARCHAR(1500)        not null,
   constraint PK_FILES primary key (files_id)
);

/*==============================================================*/
/* Index: files_PK                                              */
/*==============================================================*/
create unique index files_PK on files (
files_id
);

/*==============================================================*/
/* Index: files_posts_FK                                        */
/*==============================================================*/
create  index files_posts_FK on files (
posts_id
);

/*==============================================================*/
/* Table: posts                                                 */
/*==============================================================*/
create table posts (
   posts_id             SERIAL not null,
   disciplines_id       INT4                 not null,
   users_id             INT4                 not null,
   posts_title          VARCHAR(255)         not null,
   posts_date           DATE                 not null default CURRENT_DATE,
   posts_text           VARCHAR(2555)        not null,
   constraint PK_POSTS primary key (posts_id)
);

/*==============================================================*/
/* Index: posts_PK                                              */
/*==============================================================*/
create unique index posts_PK on posts (
posts_id
);

/*==============================================================*/
/* Index: disciplines_posts_FK                                  */
/*==============================================================*/
create  index disciplines_posts_FK on posts (
disciplines_id
);

/*==============================================================*/
/* Index: posts_users_FK                                        */
/*==============================================================*/
create  index posts_users_FK on posts (
users_id
);

/*==============================================================*/
/* Table: users                                                 */
/*==============================================================*/
create table users (
   users_id             SERIAL not null,
   users_email          VARCHAR(255)         not null,
   users_name           VARCHAR(255)         not null,
   users_password       VARCHAR(255)         not null,
   constraint PK_USERS primary key (users_id)
);

/*==============================================================*/
/* Index: users_PK                                              */
/*==============================================================*/
create unique index users_PK on users (
users_id
);

alter table files
   add constraint FK_FILES_FILES_POS_POSTS foreign key (posts_id)
      references posts (posts_id)
      on delete restrict on update restrict;

alter table posts
   add constraint FK_POSTS_DISCIPLIN_DISCIPLI foreign key (disciplines_id)
      references disciplines (disciplines_id)
      on delete restrict on update restrict;

alter table posts
   add constraint FK_POSTS_POSTS_USE_USERS foreign key (users_id)
      references users (users_id)
      on delete restrict on update restrict;

