package cn.zhku.modal;

import java.sql.Timestamp;

import javax.persistence.Entity;
import javax.persistence.GeneratedValue;
import javax.persistence.Id;

@Entity
public class Weibo_Photo {

	@Id
	@GeneratedValue
	private int Pid;
	
	private String Pname;
	private String Pphoto;
	private Timestamp Ptime;
	private int WEI_PG_id;
	public Weibo_Photo() {
		super();
		// TODO Auto-generated constructor stub
	}
	public Weibo_Photo(String pname, String pphoto, Timestamp ptime,
			int wEI_PG_id) {
		super();
		Pname = pname;
		Pphoto = pphoto;
		Ptime = ptime;
		WEI_PG_id = wEI_PG_id;
	}
	public int getPid() {
		return Pid;
	}
	public void setPid(int pid) {
		Pid = pid;
	}
	public String getPname() {
		return Pname;
	}
	public void setPname(String pname) {
		Pname = pname;
	}
	public String getPphoto() {
		return Pphoto;
	}
	public void setPphoto(String pphoto) {
		Pphoto = pphoto;
	}
	public Timestamp getPtime() {
		return Ptime;
	}
	public void setPtime(Timestamp ptime) {
		Ptime = ptime;
	}
	public int getWEI_PG_id() {
		return WEI_PG_id;
	}
	public void setWEI_PG_id(int wEI_PG_id) {
		WEI_PG_id = wEI_PG_id;
	}
	

}
