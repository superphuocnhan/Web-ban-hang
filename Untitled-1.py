s1 = input('Hãy nhập vào chuỗi s1:')

dem = {}

for char in s1:
    if char in dem:
        dem[char] += 1 
    else:
        dem[char] = 1 
print(f'Từ điển đếm số lần là {dem}')