package cn.zhku.modal;

import java.sql.Timestamp;

import javax.persistence.Entity;
import javax.persistence.GeneratedValue;
import javax.persistence.Id;

@Entity
public class Weibo_Photograph {

	@Id
	@GeneratedValue
	private int PG_id;
	
	private String WEI_Ucount;
	private String PG_name;
	private Timestamp Ptime;
	public Weibo_Photograph() {
		super();
		// TODO Auto-generated constructor stub
	}
	public Weibo_Photograph(String wEI_Ucount, String pG_name, Timestamp ptime) {
		super();
		WEI_Ucount = wEI_Ucount;
		PG_name = pG_name;
		Ptime = ptime;
	}
	public int getPG_id() {
		return PG_id;
	}
	public void setPG_id(int pG_id) {
		PG_id = pG_id;
	}
	public String getWEI_Ucount() {
		return WEI_Ucount;
	}
	public void setWEI_Ucount(String wEI_Ucount) {
		WEI_Ucount = wEI_Ucount;
	}
	public String getPG_name() {
		return PG_name;
	}
	public void setPG_name(String pG_name) {
		PG_name = pG_name;
	}
	public Timestamp getPtime() {
		return Ptime;
	}
	public void setPtime(Timestamp ptime) {
		Ptime = ptime;
	}
	
	
}
