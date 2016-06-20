package cn.zhku.modal;

import javax.persistence.Entity;
import javax.persistence.GeneratedValue;
import javax.persistence.Id;

@Entity
public class Weibo_fan {

	@Id
	@GeneratedValue
	private int Fid;
	private String Ucount;
	private String wei_ucount;
	
	public Weibo_fan() {
		super();
	}

	public Weibo_fan(String ucount, String wei_ucount) {
		super();
		Ucount = ucount;
		this.wei_ucount = wei_ucount;
	}

	public int getFid() {
		return Fid;
	}

	public void setFid(int fid) {
		Fid = fid;
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
