## Technical test for SamKnows

[Subject](https://github.com/SamKnows/backend-test)

#### Implementation choice

For this subject I choose a database approach.
Basically, I save all records (metrics) into a temporary table and I use the `DBMS` to aggregate these data.

In my opinion, It's better to use the `DBMS` instead of `PHP` for :
 * Faster
 * Memory footprint
 * Built to work with large dataset

#### Implementation details

I have 2 components in my implementation.

###### Reader

The reader has the goal to read metrics from a source and to standardize into a `Metric` object.

I also create `factory`  with a static method to create the real and best reader.
In `Symfony` (or with an other framework) this factory would not have been a static class of course.

###### Processor

The processor has the goal to read from a reader the metrics and to aggregate it.
In my case, it save the metric in database and at the end run a set of queries (in my case, 1) to aggregate these data.

#### Hypothesis and choice

I suppose the timestamp value into file never contains minutes and seconds.
So I didn't make transformation on this value.

In addition, the `packet_loss` metric is the only one in float.
So, I choose to simply multiply by 1000 and convert it to an int.
With this change, I have a standardized metric. (unit: int, name: string, value: int, timestamp: string|datetime)

Finally, I suppose imported a new batch is always a new set of date.
So, I don't try to update or re-calculate existing row.
In addition, if I would like to do that I would need previous non-aggregate data.
And the subject suppose these data don't exists anymore (in database).

#### Missing

By default mysql don't have function to calculate the median from a bunch of values.
It's possible to do it with a procedure. 
But like I was already above 2 hours, I choose to set it to 0 and not take more time to write the procedure.

#### How to run it

First, you need to install composer dependencies.
```bash
composer.phar install
```

You can run the project via a console command.
```bash
bin/console samknows:import <your_file.json>
```

You can also specify the database connection with few options.
```bash
bin/console samknows:import <your_file.json> --host localhost --dbname samknows --username root --password root
```

#### Unit tests

Several unit tests have been wrote, you can find it in `src/Tests` directory.
You can also run them via console command.
```bash
bin/phpunit
```

#### Criticism

In my opinion, the choice of `json` file is not optimal.
In `PHP` you cannot read a `json` file as a stream.
So, if the file is really large (several Go), you can have trouble to read it with `PHP`.

I suggest a format like `CSV`, with for example :
```csv
unit_id,metric_name,metric_value,metric_timestramp
1,download,1000,2014-01-01 00:00:00
1,upload,1000,2014-01-01 00:00:00
1,latency,1000,2014-01-01 00:00:00
```

With this format, the file could have a bigger size (more duplication) but you can read it with `PHP` as a stream.
So, `PHP` memory footprint should be stable and low (at least from the file reader).

```php
$stream = fopen('my_file.csv', 'w+');
while ($line = fgetcsv($stream)) {
    //do your work
}
```

#### Improvement

It's could be great to add more feedback informations.
Like use a logger during the process.

In addition, add an id (int, uuid, ...) for the batch could be great idea.
In case of trouble, you can easily remove, and re-process a batch only by knowing this id.
