-- we don't know how to generate root <with-no-name> (class Root) :(

grant select on performance_schema.* to 'mysql.session'@localhost;

grant trigger on sys.* to 'mysql.sys'@localhost;

grant audit_abort_exempt, firewall_exempt, select, system_user on *.* to 'mysql.infoschema'@localhost;

grant audit_abort_exempt, authentication_policy_admin, backup_admin, clone_admin, connection_admin, firewall_exempt, persist_ro_variables_admin, session_variables_admin, shutdown, super, system_user, system_variables_admin on *.* to 'mysql.session'@localhost;

grant audit_abort_exempt, firewall_exempt, system_user on *.* to 'mysql.sys'@localhost;

grant alter, alter routine, application_password_admin, audit_abort_exempt, audit_admin, authentication_policy_admin, backup_admin, binlog_admin, binlog_encryption_admin, clone_admin, connection_admin, create, create role, create routine, create tablespace, create temporary tables, create user, create view, delete, drop, drop role, encryption_key_admin, event, execute, file, firewall_exempt, flush_optimizer_costs, flush_status, flush_tables, flush_user_resources, group_replication_admin, group_replication_stream, index, innodb_redo_log_archive, innodb_redo_log_enable, insert, lock tables, passwordless_user_admin, persist_ro_variables_admin, process, references, reload, replication client, replication slave, replication_applier, replication_slave_admin, resource_group_admin, resource_group_user, role_admin, select, sensitive_variables_observer, service_connection_admin, session_variables_admin, set_user_id, show databases, show view, show_routine, shutdown, super, system_user, system_variables_admin, table_encryption_admin, telemetry_log_admin, trigger, update, xa_recover_admin, grant option on *.* to root@localhost;

create table adresse
(
    id           int auto_increment
        primary key,
    number       int                                 null,
    street       varchar(255)                        null,
    city         varchar(255)                        null,
    postalCode   varchar(10)                         null,
    country      varchar(255)                        null,
    createdAt    timestamp default CURRENT_TIMESTAMP null,
    modifiedAt   timestamp default CURRENT_TIMESTAMP null on update CURRENT_TIMESTAMP,
    adresse_name varchar(255)                        null
);

create table attribute
(
    id         int auto_increment
        primary key,
    name       varchar(255)                        null,
    value      varchar(255)                        null,
    createdAt  timestamp default CURRENT_TIMESTAMP null,
    modifiedAt timestamp default CURRENT_TIMESTAMP null on update CURRENT_TIMESTAMP
);

create definer = root@localhost trigger before_insert_attribute
    before insert
    on attribute
    for each row
BEGIN
    DECLARE attrCount INT;

    -- Vérifier si l'attribut avec le même nom existe déjà
SELECT COUNT(*) INTO attrCount
FROM attribute
WHERE name = NEW.name;

-- Si l'attribut existe déjà, annuler l'insertion
IF attrCount > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Erreur : L''attribut avec le nom spécifié existe déjà.';
END IF;
END;

create table categories
(
    id           int auto_increment
        primary key,
    categoryName varchar(255)                        null,
    isParent     tinyint(1)                          null,
    idParent     int                                 null,
    createdAt    timestamp default CURRENT_TIMESTAMP null,
    modifiedAt   timestamp default CURRENT_TIMESTAMP null on update CURRENT_TIMESTAMP,
    constraint categories_ibfk_1
        foreign key (idParent) references categories (id)
);

create index idParent
    on categories (idParent);

create table image
(
    id          int auto_increment
        primary key,
    weight      decimal(10, 2)                      null,
    path        varchar(255)                        null,
    type        varchar(50)                         null,
    description varchar(255)                        null,
    name        varchar(255)                        null,
    createdAt   timestamp default CURRENT_TIMESTAMP null,
    modifiedAt  timestamp default CURRENT_TIMESTAMP null on update CURRENT_TIMESTAMP
);

create table productfile
(
    id          int auto_increment
        primary key,
    name        varchar(255)                        null,
    description varchar(255)                        null,
    weight      decimal(10, 2)                      null,
    length      decimal(10, 2)                      null,
    width       decimal(10, 2)                      null,
    height      decimal(10, 2)                      null,
    stock       int                                 null,
    createdAt   timestamp default CURRENT_TIMESTAMP null,
    modifiedAt  timestamp default CURRENT_TIMESTAMP null on update CURRENT_TIMESTAMP
);

create table role
(
    id       int auto_increment
        primary key,
    roleName varchar(255) not null
);

create table user
(
    id         int auto_increment
        primary key,
    login      varchar(255)                        not null,
    password   varchar(255)                        not null,
    mail       varchar(255)                        not null,
    createdAt  timestamp default CURRENT_TIMESTAMP null,
    modifiedAt timestamp default CURRENT_TIMESTAMP null on update CURRENT_TIMESTAMP,
    lastLogin  timestamp                           null
);

create table product
(
    id            int auto_increment
        primary key,
    isDeleted     tinyint(1)                          null,
    idProductFile int                                 null,
    idCreator     int                                 null,
    idDeletor     int                                 null,
    createdAt     timestamp default CURRENT_TIMESTAMP null,
    modifiedAt    timestamp default CURRENT_TIMESTAMP null on update CURRENT_TIMESTAMP,
    constraint product_ibfk_1
        foreign key (idProductFile) references productfile (id),
    constraint product_ibfk_2
        foreign key (idCreator) references user (id),
    constraint product_ibfk_3
        foreign key (idDeletor) references user (id)
);

create table attributeproduct
(
    id          int auto_increment
        primary key,
    idAttribute int null,
    idProduct   int null,
    constraint attributeproduct_ibfk_1
        foreign key (idAttribute) references attribute (id),
    constraint attributeproduct_ibfk_2
        foreign key (idProduct) references product (id)
);

create index idAttribute
    on attributeproduct (idAttribute);

create index idProduct
    on attributeproduct (idProduct);

create table categoriesproduct
(
    id         int auto_increment
        primary key,
    idCategory int null,
    idProduct  int null,
    constraint categoriesproduct_ibfk_1
        foreign key (idCategory) references categories (id),
    constraint categoriesproduct_ibfk_2
        foreign key (idProduct) references product (id)
);

create index idCategory
    on categoriesproduct (idCategory);

create index idProduct
    on categoriesproduct (idProduct);

create index idCreator
    on product (idCreator);

create index idDeletor
    on product (idDeletor);

create index idProductFile
    on product (idProductFile);

create table productimage
(
    id          int auto_increment
        primary key,
    idImage     int                                 null,
    idProduct   int                                 null,
    isMainImage tinyint(1)                          null,
    createdAt   timestamp default CURRENT_TIMESTAMP null,
    modifiedAt  timestamp default CURRENT_TIMESTAMP null on update CURRENT_TIMESTAMP,
    constraint productimage_ibfk_1
        foreign key (idImage) references image (id),
    constraint productimage_ibfk_2
        foreign key (idProduct) references product (id)
);

create index idImage
    on productimage (idImage);

create index idProduct
    on productimage (idProduct);

create table user_profile
(
    id             int auto_increment
        primary key,
    name           varchar(255)                        null,
    firstname      varchar(255)                        null,
    birthdate      date                                null,
    sexe           varchar(10)                         null,
    profilePicPath varchar(255)                        null,
    idAdress       int                                 null,
    createdAt      timestamp default CURRENT_TIMESTAMP null,
    modifiedAt     timestamp default CURRENT_TIMESTAMP null on update CURRENT_TIMESTAMP,
    idUser         int                                 null,
    constraint fk_user_profile_user
        foreign key (idUser) references user (id),
    constraint user_profile_ibfk_1
        foreign key (idAdress) references adresse (id)
);

create index idAdress
    on user_profile (idAdress);

create table useradresse
(
    id           int auto_increment
        primary key,
    idUser       int          null,
    idAdresse    int          null,
    adresse_name varchar(255) null,
    constraint useradresse_ibfk_1
        foreign key (idUser) references user (id),
    constraint useradresse_ibfk_2
        foreign key (idAdresse) references adresse (id)
);

create index idAdresse
    on useradresse (idAdresse);

create index idUser
    on useradresse (idUser);

create table userrole
(
    id     int auto_increment
        primary key,
    userId int null,
    roleId int null,
    constraint userrole_ibfk_1
        foreign key (userId) references user (id),
    constraint userrole_ibfk_2
        foreign key (roleId) references role (id)
);

create index roleId
    on userrole (roleId);

create index userId
    on userrole (userId);

create table v_product
(
    role     varchar(255) null,
    user_id  int          null,
    username varchar(255) null
);

create table v_product_info
(
    description_image        varchar(255)   null,
    description_product_file varchar(255)   null,
    height_product_file      decimal(10, 2) null,
    id_attribute             int            null,
    id_image                 int            null,
    id_product_file          int            null,
    id_produit               int            null,
    id_produit_image         int            null,
    idCategory               int            null,
    idImage                  int            null,
    idProduct                int            null,
    isDeleted                tinyint(1)     null,
    isMainImage              tinyint(1)     null,
    length_product_file      decimal(10, 2) null,
    name_attribute           varchar(255)   null,
    name_image               varchar(255)   null,
    name_product_file        varchar(255)   null,
    path_image               varchar(255)   null,
    stock_product_file       int            null,
    type_image               varchar(50)    null,
    value_attribute          varchar(255)   null,
    weight_image             decimal(10, 2) null,
    weight_product_file      decimal(10, 2) null,
    width_product_file       decimal(10, 2) null
);

create table v_user_info
(
    birthdate_user_profile      date         null,
    city_adresse                varchar(255) null,
    country_adresse             varchar(255) null,
    firstname_user_profile      varchar(255) null,
    id_utilisateur              int          null,
    lastLogin                   timestamp    null,
    login                       varchar(255) null,
    mail                        varchar(255) null,
    name_user_profile           varchar(255) null,
    number_adresse              int          null,
    password                    varchar(255) null,
    postalCode_adresse          varchar(10)  null,
    profilePicPath_user_profile varchar(255) null,
    sexe_user_profile           varchar(10)  null,
    street_adresse              varchar(255) null
);

create definer = root@localhost view produit as
select `u`.`id` AS `user_id`, `u`.`login` AS `username`, `r`.`roleName` AS `role`
from ((`commerce`.`utilisateur` `u` join `commerce`.`userrole` `ur`
       on ((`u`.`id` = `ur`.`userId`))) join `commerce`.`role` `r` on ((`ur`.`roleId` = `r`.`id`)));

create definer = root@localhost view v_attributs_category as
select `pf`.`id`         AS `id_product_file`,
       `cp`.`idCategory` AS `idCategory`,
       `a`.`id`          AS `id_attribute`,
       `a`.`name`        AS `attribute_name`,
       `a`.`value`       AS `attribute_value`
from (((`commerce`.`productfile` `pf` join `commerce`.`attributeproduct` `ap`
        on ((`pf`.`id` = `ap`.`idProduct`))) join `commerce`.`attribute` `a`
       on ((`ap`.`idAttribute` = `a`.`id`))) join `commerce`.`categoriesproduct` `cp`
      on ((`pf`.`id` = `cp`.`idProduct`)));

create definer = root@localhost view vue_produit_info as
select `p`.`id`           AS `id_produit`,
       `p`.`isDeleted`    AS `isDeleted`,
       `pf`.`id`          AS `id_product_file`,
       `pf`.`name`        AS `name_product_file`,
       `pf`.`description` AS `description_product_file`,
       `pf`.`weight`      AS `weight_product_file`,
       `pf`.`length`      AS `length_product_file`,
       `pf`.`width`       AS `width_product_file`,
       `pf`.`height`      AS `height_product_file`,
       `pf`.`stock`       AS `stock_product_file`,
       `pi`.`id`          AS `id_produit_image`,
       `pi`.`idImage`     AS `idImage`,
       `pi`.`isMainImage` AS `isMainImage`,
       `i`.`id`           AS `id_image`,
       `i`.`weight`       AS `weight_image`,
       `i`.`path`         AS `path_image`,
       `i`.`type`         AS `type_image`,
       `i`.`description`  AS `description_image`,
       `i`.`name`         AS `name_image`,
       `a`.`id`           AS `id_attribute`,
       `a`.`name`         AS `name_attribute`,
       `a`.`value`        AS `value_attribute`,
       `cp`.`idCategory`  AS `idCategory`,
       `cp`.`idProduct`   AS `idProduct`
from ((((((`commerce`.`product` `p` left join `commerce`.`productfile` `pf`
           on ((`p`.`idProductFile` = `pf`.`id`))) left join `commerce`.`productimage` `pi`
          on ((`p`.`id` = `pi`.`idProduct`))) left join `commerce`.`image` `i`
         on ((`pi`.`idImage` = `i`.`id`))) left join `commerce`.`attributeproduct` `ap`
        on ((`p`.`id` = `ap`.`idProduct`))) left join `commerce`.`attribute` `a`
       on ((`ap`.`idAttribute` = `a`.`id`))) left join `commerce`.`categoriesproduct` `cp`
      on ((`p`.`id` = `cp`.`idProduct`)));

create definer = root@localhost view vue_utilisateur_info as
select `u`.`id`              AS `id_utilisateur`,
       `u`.`login`           AS `login`,
       `u`.`password`        AS `password`,
       `u`.`mail`            AS `mail`,
       `u`.`lastLogin`       AS `lastLogin`,
       `up`.`name`           AS `name_user_profile`,
       `up`.`firstname`      AS `firstname_user_profile`,
       `up`.`birthdate`      AS `birthdate_user_profile`,
       `up`.`sexe`           AS `sexe_user_profile`,
       `up`.`profilePicPath` AS `profilePicPath_user_profile`,
       `a`.`number`          AS `number_adresse`,
       `a`.`street`          AS `street_adresse`,
       `a`.`city`            AS `city_adresse`,
       `a`.`postalCode`      AS `postalCode_adresse`,
       `a`.`country`         AS `country_adresse`
from (((`commerce`.`utilisateur` `u` join `commerce`.`user_profile` `up`
        on ((`u`.`idUserProfil` = `up`.`id`))) join `commerce`.`useradresse` `ua`
       on ((`u`.`id` = `ua`.`idUser`))) join `commerce`.`adresse` `a` on ((`ua`.`idAdresse` = `a`.`id`)));

