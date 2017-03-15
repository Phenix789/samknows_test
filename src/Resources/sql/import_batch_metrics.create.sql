CREATE TEMPORARY TABLE import_batch_metrics (
#CREATE TABLE IF NOT EXISTS import_batch_metrics (
  id INT NOT NULL AUTO_INCREMENT,
  unit_id INT NOT NULL,
  metric_name VARCHAR(20),
  metric_value INT NOT NULL,
  metric_time TIMESTAMP NOT NULL,
  PRIMARY KEY (id)
)
;
