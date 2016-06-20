package cn.zhku.modal;

import javax.persistence.Entity;
import javax.persistence.GeneratedValue;
import javax.persistence.Id;

@Entity
public class Weibo_Concern {

	@Id
	@GeneratedValue
	private int Cid;
	
	private String Ucount;
	private String wei_ucount;
	
	public Weibo_Concern() {
		super();
	}
	
	public Weibo_Concern(String ucount, String wei_ucount) {
		super();
		Ucount = ucount;
		this.wei_ucount = wei_ucount;
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

	public String getWei_ucount() {
		return wei_ucount;
	}

	public void setWei_ucount(String wei_ucount) {
		this.wei_ucount = wei_ucount;
	}
	
	
}
