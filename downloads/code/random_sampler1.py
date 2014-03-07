import random

def random_sampler(filename, k):
	sample = []
	with open(filename, 'rb') as f:
		linecount = sum(1 for line in f)
		f.seek(0)

		random_linenos = sorted(random.sample(xrange(linecount), k), reverse = True)
		lineno = random_linenos.pop()
		for n, line in enumerate(f):
			if n == lineno:
				sample.append(line.rstrip())
				if len(random_linenos) > 0:
					lineno = random_linenos.pop()
				else:
					break
	return sample
