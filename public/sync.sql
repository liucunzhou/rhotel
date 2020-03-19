--
---- fa_city
--
create table tk_city like yazhi.fa_city;
insert into tk_city select * from yazhi.fa_city;

--
-- fa_hotel
--
create table fa_hotel like yazhi.fa_hotel;
insert into fa_hotel select * from yazhi.fa_hotel;

--
-- fa_hotel_background
--
create table fa_hotel_background like yazhi.fa_hotel_background;
insert into fa_hotel_background select * from yazhi.fa_hotel_background;

--
-- fa_hotel_banner
--
create table fa_hotel_banner like yazhi.fa_hotel_banner;
insert into fa_hotel_banner select * from yazhi.fa_hotel_banner;

--
-- fa_hotel_image
--
create table fa_hotel_image like yazhi.fa_hotel_image;
insert into fa_hotel_image select * from yazhi.fa_hotel_image;

--
-- fa_room           
--
create table fa_room like yazhi.fa_room;
insert into fa_room select * from yazhi.fa_room;