package cn.zhku.modal;

import javax.persistence.Entity;
import javax.persistence.GeneratedValue;
import javax.persistence.Id;

@Entity
public class Weibo_Comment {

	@Id
	@GeneratedValue
	private int Cid;
	
	private String Ucount;
	private int Tid;
	private String Ccontent;
	
	public Weibo_Comment() {
		super();
	}

	public Weibo_Comment(String ucount, int tid, String ccontent) {
		super();
		Ucount = ucount;
		Tid = tid;
		Ccontent = ccontent;
	}

	public int getCid() {
		return Cid;
	}

	public void setCid(int cid) {
		Cid = cid;
	}

	public String getUcount() {
		return Ucount;
	}

	public void setUcount(String ucount) {
		Ucount = ucount;
	}

	public int getTid() {
		return Tid;
	}

	public void setTid(int tid) {
		Tid = tid;
	}

	public String getCcontent() {
		return Ccontent;
	}

	public void setCcontent(String ccontent) {
		Ccontent = ccontent;
	}
}
